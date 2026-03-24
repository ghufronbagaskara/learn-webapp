import requests
import pandas as pd
import time
import urllib.parse

QUERY = "mouse b100"
MAX_ITEMS = 200
ROWS = 60

search_url = "https://gql.tokopedia.com/graphql/SearchProductQuery"

headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    "Content-Type": "application/json",
    "Referer": "https://www.tokopedia.com/"
}

products_all = []
start = 0

while len(products_all) < MAX_ITEMS:

    params = (
        f"device=desktop"
        f"&navsource="
        f"&q={urllib.parse.quote(QUERY)}"
        f"&rows={ROWS}"
        f"&start={start}"
        f"&source=search"
    )

    payload = [{
        "operationName": "SearchProductQuery",
        "variables": {
            "params": params
        },
        "query": """
        query SearchProductQuery($params: String!) {
          searchProduct(params: $params) {
            products {
              name
              price
              url
              rating
              shop {
                name
                city
              }
            }
          }
        }
        """
    }]

    r = requests.post(search_url, headers=headers, json=payload)

    data = r.json()

    if "data" not in data[0]:
        print("Blocked or API changed")
        print(data)
        break

    products = data[0]["data"]["searchProduct"]["products"]

    if not products:
        break

    for p in products:
        products_all.append({
            "name": p["name"],
            "price": p["price"],
            "shop": p["shop"]["name"],
            "location": p["shop"]["city"],
            "rating": p["rating"],
            "link": p["url"]
        })

    print(f"Collected {len(products_all)} products")

    start += ROWS
    time.sleep(1)

df = pd.DataFrame(products_all[:MAX_ITEMS])
df.to_csv("tokopedia_results.csv", index=False, encoding="utf-8-sig")

print("Saved", len(df), "products")