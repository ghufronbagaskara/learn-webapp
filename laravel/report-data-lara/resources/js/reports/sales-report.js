let chartInstance = null;

function formatLocalDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
}

function renderChart(labels, data) {
    const canvas = document.getElementById("revenueChart");
    if (!canvas || !window.Chart) {
        return;
    }

    const ctx = canvas.getContext("2d");
    chartInstance?.destroy();

    chartInstance = new window.Chart(ctx, {
        type: "line",
        data: {
            labels,
            datasets: [
                {
                    label: "Revenue",
                    data,
                    borderColor: "#4F46E5",
                    backgroundColor: "rgba(79,70,229,0.08)",
                    borderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: "#F1F5F9" } },
                y: { grid: { color: "#F1F5F9" }, beginAtZero: true },
            },
        },
    });
}

function initDateRangePicker(startDate, endDate, wire) {
    const input = document.getElementById("dateRange");
    if (!input || !window.flatpickr || !wire) {
        return;
    }

    if (input._flatpickr) {
        input._flatpickr.destroy();
    }

    window.flatpickr(input, {
        mode: "range",
        altInput: true,
        altFormat: "d M Y",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        conjunction: " - ",
        onChange(selectedDates) {
            if (selectedDates.length === 2) {
                wire.set("draftStartDate", formatLocalDate(selectedDates[0]));
                wire.set("draftEndDate", formatLocalDate(selectedDates[1]));
            }
        },
    });
}

function initStatusSelect(currentStatus, wire) {
    const select = document.getElementById("statusFilter");
    if (!select) {
        return;
    }

    select.value = currentStatus || "";
}

function renderPivot(pivotRows) {
    const output = window.$("#pivotOutput");
    if (!output.length || !window.$.pivotUtilities) {
        return;
    }

    output.empty();
    output.pivotUI(
        pivotRows,
        {
            rows: ["category"],
            cols: ["month"],
            aggregatorName: "Sum",
            vals: ["revenue"],
            rendererName: "Table",
        },
        true,
    );
}

function initReportingUi(payload, wire) {
    initDateRangePicker(payload.startDate, payload.endDate, wire);
    initStatusSelect(payload.status, wire);
    renderChart(payload.chartLabels, payload.chartData);
    renderPivot(payload.pivotRows);
}

function boot(wire) {
    if (!wire) {
        return;
    }

    const init = () => {
        const host = document.getElementById("sales-report-page");
        if (!host) {
            return;
        }

        initReportingUi(JSON.parse(host.dataset.payload || "{}"), wire);

        Livewire.on(
            "filtersApplied",
            ({
                startDate,
                endDate,
                status,
                chartLabels,
                chartData,
                pivotRows,
            }) => {
                initReportingUi(
                    {
                        startDate,
                        endDate,
                        status,
                        chartLabels,
                        chartData,
                        pivotRows,
                    },
                    wire,
                );
            },
        );

        Livewire.on(
            "filtersReset",
            ({
                startDate,
                endDate,
                status,
                chartLabels,
                chartData,
                pivotRows,
            }) => {
                initReportingUi(
                    {
                        startDate,
                        endDate,
                        status,
                        chartLabels,
                        chartData,
                        pivotRows,
                    },
                    wire,
                );
            },
        );
    };

    if (window.Livewire) {
        init();
    }

    document.addEventListener("livewire:init", init, { once: true });
}

window.ReportSalesPage = { boot };
