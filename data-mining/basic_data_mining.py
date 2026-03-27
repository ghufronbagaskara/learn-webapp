"""
Coffee Shop Monthly Sales Chart
Adaptasi dari kode mentor, dengan tambahan:
1) sumber data dari coffee_sales.csv,
2) klasifikasi scikit-learn,
3) 1-2 kalimat insight dari grafik.
"""

import sys
from pathlib import Path

import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
from sklearn.compose import ColumnTransformer
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report
from sklearn.model_selection import train_test_split
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder

# ── Load CSV file (coffee_sales.csv) ─────────────────────────────────────────
csv_path = Path(__file__).with_name("coffee_sales.csv")
if not csv_path.exists():
    print(f"File tidak ditemukan: {csv_path}")
    sys.exit(1)

try:
    df = pd.read_csv(csv_path, sep=";")
except Exception as e:
    print(f"Error reading file: {e}")
    sys.exit(1)

df["date"] = pd.to_datetime(df["date"], dayfirst=True, errors="coerce")
df["money_num"] = (
    df["money"]
    .astype(str)
    .str.replace("R", "", regex=False)
    .str.replace(",", ".", regex=False)
    .str.strip()
)
df["money_num"] = pd.to_numeric(df["money_num"], errors="coerce")

df = df.dropna(subset=["date", "money_num"]).copy()

# ── Aggregate by month ────────────────────────────────────────────────────────
df["month"] = df["date"].dt.to_period("M")
monthly = (
    df.groupby("month")
    .agg(revenue=("money_num", "sum"), orders=("money_num", "count"))
    .reset_index()
    .sort_values("month")
)
monthly["avg_ticket"] = monthly["revenue"] / monthly["orders"]

month_labels = [str(m) for m in monthly["month"]]
month_labels = [pd.Period(m).strftime("%b %Y") for m in month_labels]
revenues = monthly["revenue"].values
orders = monthly["orders"].values
avg_ticket = monthly["avg_ticket"].values

print("Ringkasan statistik (Numpy + Pandas):")
print(f"Jumlah transaksi: {len(df)}")
print(f"Rata-rata nilai transaksi: {np.mean(df['money_num']):.2f}")
print(f"Median nilai transaksi: {np.median(df['money_num']):.2f}")
print(f"Standar deviasi nilai transaksi: {np.std(df['money_num']):.2f}")

# ── Style (dipertahankan dari kode mentor) ───────────────────────────────────
BG = "#1C1410"
PANEL = "#251C17"
ESPRESSO = "#5C3D2E"
CARAMEL = "#C8843A"
CREAM = "#F5E6C8"
MUTED = "#9B8B7A"
ACCENT = "#E8A84E"

plt.rcParams.update({
    "font.family": "serif",
    "axes.facecolor": PANEL,
    "figure.facecolor": BG,
    "text.color": CREAM,
    "axes.labelcolor": CREAM,
    "xtick.color": MUTED,
    "ytick.color": MUTED,
    "axes.edgecolor": ESPRESSO,
    "grid.color": ESPRESSO,
    "grid.linestyle": "--",
    "grid.alpha": 0.4,
})

fig, axes = plt.subplots(3, 1, figsize=(max(10, len(month_labels) * 2), 11), gridspec_kw={"hspace": 0.55})

fig.suptitle("Cape Town Coffee Shop  -  Monthly Sales", fontsize=16, fontweight="bold", color=CREAM, y=0.97)

x = np.arange(len(month_labels))

# ── Chart 1 - Revenue ─────────────────────────────────────────────────────────
ax1 = axes[0]
bars1 = ax1.bar(x, revenues, color=CARAMEL, width=0.5, edgecolor=ACCENT, linewidth=0.8, zorder=3)
ax1.plot(x, revenues, color=CREAM, linewidth=1.4, marker="o", markersize=6, zorder=4)
for bar, val in zip(bars1, revenues):
    ax1.text(
        bar.get_x() + bar.get_width() / 2,
        bar.get_height() + revenues.max() * 0.01,
        f"R{val:,.0f}",
        ha="center",
        va="bottom",
        fontsize=8,
        color=CREAM,
        fontweight="bold",
    )
ax1.set_title("Monthly Revenue (ZAR)", color=ACCENT, fontsize=11, pad=8, loc="left")
ax1.set_xticks(x)
ax1.set_xticklabels(month_labels, fontsize=10)
ax1.set_ylabel("Revenue (R)", fontsize=9)
ax1.set_ylim(0, revenues.max() * 1.18)
ax1.yaxis.set_major_formatter(plt.FuncFormatter(lambda v, _: f"R{v:,.0f}"))
ax1.grid(axis="y", zorder=0)
ax1.axhline(revenues.mean(), color=ACCENT, linestyle=":", linewidth=1.2, label=f"Avg  R{revenues.mean():,.0f}")
ax1.legend(fontsize=8, facecolor=PANEL, edgecolor=ESPRESSO, labelcolor=CREAM, loc="upper left")

# ── Chart 2 - Orders ──────────────────────────────────────────────────────────
ax2 = axes[1]
bars2 = ax2.bar(x, orders, color=ESPRESSO, width=0.5, edgecolor=CARAMEL, linewidth=0.8, zorder=3)
ax2.plot(x, orders, color=CARAMEL, linewidth=1.4, marker="s", markersize=6, zorder=4)
for bar, val in zip(bars2, orders):
    ax2.text(
        bar.get_x() + bar.get_width() / 2,
        bar.get_height() + orders.max() * 0.01,
        str(val),
        ha="center",
        va="bottom",
        fontsize=9,
        color=CREAM,
        fontweight="bold",
    )
ax2.set_title("Number of Orders", color=ACCENT, fontsize=11, pad=8, loc="left")
ax2.set_xticks(x)
ax2.set_xticklabels(month_labels, fontsize=10)
ax2.set_ylabel("Transactions", fontsize=9)
ax2.set_ylim(0, orders.max() * 1.2)
ax2.grid(axis="y", zorder=0)
ax2.axhline(orders.mean(), color=ACCENT, linestyle=":", linewidth=1.2, label=f"Avg  {orders.mean():.1f} orders")
ax2.legend(fontsize=8, facecolor=PANEL, edgecolor=ESPRESSO, labelcolor=CREAM, loc="upper left")

# ── Chart 3 - Avg Ticket ──────────────────────────────────────────────────────
ax3 = axes[2]
ax3.fill_between(x, avg_ticket, alpha=0.25, color=CARAMEL, zorder=2)
ax3.plot(x, avg_ticket, color=CARAMEL, linewidth=2, marker="D", markersize=7, zorder=4)
ax3.axhline(avg_ticket.mean(), color=ACCENT, linestyle=":", linewidth=1.2, label=f"Overall avg  R{avg_ticket.mean():.2f}")
for xi, val in zip(x, avg_ticket):
    ax3.text(
        xi,
        val + avg_ticket.max() * 0.01,
        f"R{val:.2f}",
        ha="center",
        va="bottom",
        fontsize=8,
        color=CREAM,
        fontweight="bold",
    )
ax3.set_title("Average Spend per Transaction (ZAR)", color=ACCENT, fontsize=11, pad=8, loc="left")
ax3.set_xticks(x)
ax3.set_xticklabels(month_labels, fontsize=10)
ax3.set_ylabel("Avg Ticket (R)", fontsize=9)
ax3.set_ylim(avg_ticket.min() - 2, avg_ticket.max() + 4)
ax3.yaxis.set_major_formatter(plt.FuncFormatter(lambda v, _: f"R{v:.0f}"))
ax3.grid(axis="y", zorder=0)
ax3.legend(fontsize=8, facecolor=PANEL, edgecolor=ESPRESSO, labelcolor=CREAM, loc="upper left")

# ── Footer ────────────────────────────────────────────────────────────────────
fig.text(
    0.5,
    0.01,
    f"Source: {csv_path.name}  |  "
    f"Total revenue: R{revenues.sum():,.2f}  |  "
    f"Total orders: {orders.sum()}  |  "
    f"Overall avg ticket: R{(revenues.sum()/orders.sum()):.2f}",
    ha="center",
    fontsize=9,
    color=MUTED,
    style="italic",
)

# ── Save plot ─────────────────────────────────────────────────────────────────
out_path = csv_path.parent / (csv_path.stem + "_monthly_chart.png")
plt.savefig(out_path, dpi=150, bbox_inches="tight", facecolor=BG)
print(f"Chart saved to: {out_path}")
plt.show()

# ── Insight (tambahan mentor: 1-2 kalimat hasil graph) ──────────────────────
peak_idx = int(np.argmax(revenues))
quiet_idx = int(np.argmin(orders))
print("\nInsight Graph:")
print(f"1) Pendapatan bulanan tertinggi terjadi pada {month_labels[peak_idx]}, menunjukkan tren penjualan sedang kuat di periode tersebut.")
print(f"2) Jumlah order paling rendah ada di {month_labels[quiet_idx]}, sehingga periode ini cocok dijadikan fokus promo untuk menaikkan traffic transaksi.")

# ── Klasifikasi scikit-learn (tambahan tugas) ────────────────────────────────
model_df = df[["hour_of_day", "money_num", "Weekday", "cash_type", "Time_of_Day"]].copy()
model_df["hour_of_day"] = pd.to_numeric(model_df["hour_of_day"], errors="coerce")
model_df["cash_type"] = model_df["cash_type"].fillna("unknown")
model_df = model_df.dropna(subset=["hour_of_day", "money_num", "Weekday", "Time_of_Day"])

X = model_df[["hour_of_day", "money_num", "Weekday", "cash_type"]]
y = model_df["Time_of_Day"]

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)

preprocessor = ColumnTransformer(
    transformers=[
        ("cat", OneHotEncoder(handle_unknown="ignore"), ["Weekday", "cash_type"]),
        ("num", "passthrough", ["hour_of_day", "money_num"]),
    ]
)

clf = Pipeline(
    steps=[
        ("preprocess", preprocessor),
        ("model", RandomForestClassifier(n_estimators=200, random_state=42)),
    ]
)

clf.fit(X_train, y_train)
y_pred = clf.predict(X_test)

print("\nHasil Klasifikasi (prediksi Time_of_Day):")
print(f"Akurasi: {accuracy_score(y_test, y_pred):.4f}")
print(classification_report(y_test, y_pred))

