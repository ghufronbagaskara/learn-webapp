import time
import json
import random
import pandas as pd
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException, ElementClickInterceptedException
from webdriver_manager.chrome import ChromeDriverManager

# ==============================
# CONFIGURATION
# ==============================
TARGET_ITEM_COUNT = 100
SEARCH_QUERY = "mouse b100"

# ==============================
# SETUP SELENIUM
# ==============================
chrome_options = Options()
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-notifications")
chrome_options.add_argument("--disable-blink-features=AutomationControlled")
chrome_options.add_experimental_option("excludeSwitches", ['enable-automation'])
# Uncomment to run headless (no browser window):
# chrome_options.add_argument("--headless=new")

driver = webdriver.Chrome(
    service=ChromeService(ChromeDriverManager().install()),
    options=chrome_options
)
driver.set_window_size(1366, 768)


def click_load_more(driver) -> bool:
    """
    Tries multiple strategies to find and click the 'Muat Lebih Banyak' button.
    Returns True if clicked, False if not found.
    """
    strategies = [
        # 1. Exact span text match (most reliable)
        (By.XPATH, "//button[.//span[normalize-space(text())='Muat Lebih Banyak']]"),
        # 2. Button contains text directly
        (By.XPATH, "//button[contains(normalize-space(.), 'Muat Lebih Banyak')]"),
        # 3. data-unify attribute (Tokopedia's design system attribute - stable)
        (By.XPATH, "//button[@data-unify='Button'][contains(., 'Muat')]"),
        # 4. CSS selector targeting span inside button
        (By.CSS_SELECTOR, "button span"),  # filtered below
    ]

    for by, selector in strategies[:3]:  # Skip strategy 4 for now, handled separately
        try:
            btn = WebDriverWait(driver, 5).until(
                EC.presence_of_element_located((by, selector))
            )
            # Scroll button into center of viewport
            driver.execute_script("arguments[0].scrollIntoView({block: 'center', inline: 'center'});", btn)
            time.sleep(1)

            # Try regular click first, fall back to JS click
            try:
                WebDriverWait(driver, 3).until(EC.element_to_be_clickable((by, selector)))
                btn.click()
            except ElementClickInterceptedException:
                print("⚠️  Regular click intercepted, using JS click...")
                driver.execute_script("arguments[0].click();", btn)

            print(f"✅ Clicked 'Muat Lebih Banyak' via strategy: {selector}")
            return True

        except (TimeoutException, NoSuchElementException):
            continue

    # Strategy 4: find all buttons, check text manually
    try:
        all_buttons = driver.find_elements(By.TAG_NAME, "button")
        for btn in all_buttons:
            if "muat lebih banyak" in btn.text.strip().lower():
                driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", btn)
                time.sleep(1)
                driver.execute_script("arguments[0].click();", btn)
                print("✅ Clicked 'Muat Lebih Banyak' via full button scan")
                return True
    except Exception as e:
        print(f"⚠️  Button scan failed: {e}")

    return False


def scroll_page_gradually(driver, steps=6, delay=0.8):
    """Scrolls down in steps to trigger lazy loading."""
    scroll_height = driver.execute_script("return document.body.scrollHeight")
    for i in range(1, steps + 1):
        target = int((i / steps) * scroll_height)
        driver.execute_script(f"window.scrollTo(0, {target});")
        time.sleep(delay)


def extract_data(soup, seen_links):
    """Parses product cards and deduplicates by link."""
    product_cards = soup.select("div.css-5wh65g")
    new_results = []

    for card in product_cards:
        a_tag = card.select_one("a[data-testid='ads-product-clickable']") or card.select_one("a")
        link = a_tag["href"] if a_tag else ""

        if not link or link in seen_links:
            continue

        seen_links.add(link)

        name_tag  = card.select_one("div[data-testid='spnSRPProdName']")
        price_tag = card.select_one("div[data-testid='spnSRPProdPrice']")

        shop_info = card.select("span.css-1rnss99")
        location  = shop_info[0].text.strip() if len(shop_info) > 0 else ""
        shop      = shop_info[1].text.strip() if len(shop_info) > 1 else ""

        rating = card.select_one("span.ff983351")
        sold   = card.select_one("span[data-testid='spnSRPProdSoldCnt']")

        new_results.append({
            'name':     name_tag.text.strip()  if name_tag  else "",
            'price':    price_tag.text.strip() if price_tag else "",
            'shop':     shop,
            'location': location,
            'sold':     sold.text.strip()   if sold   else "",
            'rating':   rating.text.strip() if rating else "",
            'link':     link,
        })

    return new_results


# ==============================
# MAIN SCRAPING ENGINE
# ==============================
all_data   = []
seen_links = set()
url = f"https://www.tokopedia.com/search?st=product&q={SEARCH_QUERY.replace(' ', '%20')}"

try:
    driver.get(url)
    print(f"🚀 Starting scrape for: '{SEARCH_QUERY}'")
    print(f"🎯 Target: {TARGET_ITEM_COUNT} items\n")

    no_progress_streak = 0  # Detect stalls

    while len(all_data) < TARGET_ITEM_COUNT:
        # ── 1. Scroll gradually to trigger lazy-loading ──────────────────
        scroll_page_gradually(driver, steps=6, delay=0.8)

        # ── 2. Extract current page content ──────────────────────────────
        soup      = BeautifulSoup(driver.page_source, 'html.parser')
        new_items = extract_data(soup, seen_links)
        all_data.extend(new_items)

        print(f"📦 Progress: {len(all_data)} / {TARGET_ITEM_COUNT}  (+{len(new_items)} new)")

        if not new_items:
            no_progress_streak += 1
            print(f"⚠️  No new items found ({no_progress_streak}/3 stall limit)")
            if no_progress_streak >= 3:
                print("🏁 Stalled — stopping scrape.")
                break
        else:
            no_progress_streak = 0

        if len(all_data) >= TARGET_ITEM_COUNT:
            break

        # ── 3. Try clicking 'Muat Lebih Banyak' ──────────────────────────
        clicked = click_load_more(driver)

        if clicked:
            # Wait for new content to load
            wait_time = random.uniform(2.5, 4.5)
            print(f"⏳ Waiting {wait_time:.1f}s for new content...")
            time.sleep(wait_time)
        else:
            # Button not found — check if truly at bottom
            print("🔍 Button not found, checking scroll position...")
            prev_height = driver.execute_script("return document.body.scrollHeight")
            driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
            time.sleep(2.5)
            new_height = driver.execute_script("return document.body.scrollHeight")

            if new_height == prev_height:
                print("🏁 Reached bottom of page — no more items to load.")
                break
            else:
                print("📜 Page grew after scroll, continuing...")

finally:
    driver.quit()
    print("\n🔌 Browser closed.")

# ==============================
# SAVE RESULTS
# ==============================
if all_data:
    final_df = pd.DataFrame(all_data[:TARGET_ITEM_COUNT])
    output_file = 'tokopedia_results.csv'
    final_df.to_csv(output_file, index=False, encoding='utf-8-sig')
    print(f"\n✨ SUCCESS! Saved {len(final_df)} items to '{output_file}'")
    print(final_df[['name', 'price', 'shop', 'location']].head(5).to_string())
else:
    print("\n❌ No data captured.")