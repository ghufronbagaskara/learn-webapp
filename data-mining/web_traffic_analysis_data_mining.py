"""Tugas 2 Data Mining: messy_daily_website_visitors.csv.

Isi tugas:
1) Bandingkan beberapa model regresi dan simpulkan model terbaik.
2) Jawab pertanyaan:
   a. Total returning visits dan rata-rata unique visits setelah data dibersihkan.
   b. Rata-rata page loads untuk setiap unique visit.
   c. Jumlah page loads per hari.
"""

from pathlib import Path

import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestRegressor
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
from sklearn.model_selection import train_test_split
from sklearn.neighbors import KNeighborsRegressor
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import StandardScaler


def clean_visitors_data(csv_path: Path) -> pd.DataFrame:
    df = pd.read_csv(csv_path)

    df = df.rename(
        columns={
            "Row": "row",
            "Day": "day",
            "Day.Of.Week": "day_of_week",
            "Date": "date",
            "Page.Loads": "page_loads",
            "Unique.Visits": "unique_visits",
            "First.Time.Visits": "first_time_visits",
            "Returning.Visits": "returning_visits",
        }
    )

    numeric_cols = [
        "row",
        "day_of_week",
        "page_loads",
        "unique_visits",
        "first_time_visits",
        "returning_visits",
    ]

    for col in numeric_cols:
        df[col] = pd.to_numeric(
            df[col].astype(str).str.replace(",", "", regex=False).str.strip(),
            errors="coerce",
        )

    df["date"] = pd.to_datetime(df["date"], errors="coerce")

    # Buang baris dengan data inti yang hilang
    cleaned = df.dropna(subset=["day", "day_of_week", "page_loads", "unique_visits", "returning_visits"]).copy()

    # Hindari pembagian dengan nol
    cleaned = cleaned[cleaned["unique_visits"] > 0].copy()
    return cleaned


def answer_questions(cleaned: pd.DataFrame) -> None:
    total_returning_visits = cleaned["returning_visits"].sum()
    avg_unique_visits = cleaned["unique_visits"].mean()

    avg_page_loads_per_unique_visit = (cleaned["page_loads"] / cleaned["unique_visits"]).mean()

    day_order = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]
    page_loads_per_day = cleaned.groupby("day", as_index=False)["page_loads"].sum()
    page_loads_per_day["day"] = pd.Categorical(page_loads_per_day["day"], categories=day_order, ordered=True)
    page_loads_per_day = page_loads_per_day.sort_values("day")

    print("\nJawaban Pertanyaan:")
    print(f"a) Total Returning Visits: {total_returning_visits:,.0f}")
    print(f"   Rata-rata Unique Visits (setelah cleaning): {avg_unique_visits:,.2f}")
    print(f"b) Rata-rata jumlah Page Loads untuk setiap Unique Visit: {avg_page_loads_per_unique_visit:.4f}")
    print("c) Jumlah Page Loads per hari:")
    for _, row in page_loads_per_day.iterrows():
        print(f"   - {row['day']}: {row['page_loads']:,.0f}")

    # Visualisasi untuk poin (c)
    plt.figure(figsize=(10, 5))
    plt.bar(page_loads_per_day["day"], page_loads_per_day["page_loads"], color="#2ca02c")
    plt.title("Jumlah Page Loads per Hari")
    plt.xlabel("Hari")
    plt.ylabel("Total Page Loads")
    plt.xticks(rotation=20)
    plt.tight_layout()
    plt.show()


def compare_regression_models(cleaned: pd.DataFrame) -> None:
    # Target: page_loads
    # Fitur utama trafik harian
    features = ["unique_visits", "first_time_visits", "returning_visits", "day_of_week"]
    model_df = cleaned.dropna(subset=features + ["page_loads"]).copy()

    X = model_df[features]
    y = model_df["page_loads"]

    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42
    )

    models = {
        "Linear Regression": Pipeline(
            steps=[
                ("scaler", StandardScaler()),
                ("model", LinearRegression()),
            ]
        ),
        "KNN Regressor": Pipeline(
            steps=[
                ("scaler", StandardScaler()),
                ("model", KNeighborsRegressor(n_neighbors=7)),
            ]
        ),
        "Random Forest Regressor": RandomForestRegressor(
            n_estimators=300,
            random_state=42,
        ),
    }

    results = []
    for name, model in models.items():
        model.fit(X_train, y_train)
        pred = model.predict(X_test)

        mae = mean_absolute_error(y_test, pred)
        rmse = np.sqrt(mean_squared_error(y_test, pred))
        r2 = r2_score(y_test, pred)

        results.append({"model": name, "MAE": mae, "RMSE": rmse, "R2": r2})

    results_df = pd.DataFrame(results).sort_values(by=["R2", "RMSE"], ascending=[False, True])
    best_model = results_df.iloc[0]

    print("\nPerbandingan Model Regresi:")
    print(results_df.to_string(index=False, formatters={
        "MAE": "{:.3f}".format,
        "RMSE": "{:.3f}".format,
        "R2": "{:.4f}".format,
    }))

    print("\nKesimpulan Model:")
    print(
        f"Model yang paling cocok adalah {best_model['model']} karena memiliki "
        f"R2 tertinggi ({best_model['R2']:.4f}) dan error relatif rendah (RMSE {best_model['RMSE']:.3f})."
    )

    # Insight dan aksi dari hasil analisis
    print("\nInsight dan tindakan yang bisa dilakukan:")
    print("1) Karena page loads dipengaruhi kuat oleh unique visits, strategi akuisisi pengunjung baru akan langsung menaikkan traffic.")
    print("2) Hari dengan page loads tinggi bisa diprioritaskan untuk campaign atau jadwal konten utama agar konversi lebih optimal.")

    # Visualisasi metrik model
    fig, axes = plt.subplots(1, 2, figsize=(12, 4))

    axes[0].bar(results_df["model"], results_df["R2"], color="#1f77b4")
    axes[0].set_title("Perbandingan R2")
    axes[0].set_ylabel("R2")
    axes[0].tick_params(axis="x", rotation=20)

    axes[1].bar(results_df["model"], results_df["RMSE"], color="#ff7f0e")
    axes[1].set_title("Perbandingan RMSE")
    axes[1].set_ylabel("RMSE (lebih kecil lebih baik)")
    axes[1].tick_params(axis="x", rotation=20)

    plt.tight_layout()
    plt.show()


def main() -> None:
    csv_path = Path(__file__).with_name("messy_daily_website_visitors.csv")
    if not csv_path.exists():
        raise FileNotFoundError(f"File tidak ditemukan: {csv_path}")

    cleaned = clean_visitors_data(csv_path)
    answer_questions(cleaned)
    compare_regression_models(cleaned)


if __name__ == "__main__":
    main()
