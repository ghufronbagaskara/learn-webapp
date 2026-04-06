function initPivotDateRange(startDate, endDate, wire) {
    const input = document.getElementById("pivotDateRange");
    if (!input || !window.flatpickr || !wire) {
        return;
    }

    if (input._flatpickr) {
        input._flatpickr.destroy();
    }

    window.flatpickr(input, {
        mode: "range",
        dateFormat: "Y-m-d",
        defaultDate: [startDate, endDate],
        onChange(selectedDates) {
            if (selectedDates.length === 2) {
                wire.set(
                    "startDate",
                    selectedDates[0].toISOString().slice(0, 10),
                );
                wire.set(
                    "endDate",
                    selectedDates[1].toISOString().slice(0, 10),
                );
            }
        },
    });
}

function initPivotStatus(currentStatus, wire) {
    const el = window.$("#pivotStatusFilter");
    if (!el.length || !el.select2 || !wire) {
        return;
    }

    el.off("change.pivoting");

    el.select2({
        placeholder: "All Status",
        allowClear: true,
        width: "100%",
    });

    el.val(currentStatus || "").trigger("change.select2");

    el.on("change.pivoting", function onPivotStatusChange() {
        wire.set("status", window.$(this).val() || "");
    });
}

function renderPivotOnly(pivotRows) {
    const output = window.$("#pivotOnlyOutput");
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

function boot(wire) {
    if (!wire) {
        return;
    }

    const init = () => {
        const host = document.getElementById("pivot-report-page");
        if (!host) {
            return;
        }

        const payload = JSON.parse(host.dataset.payload || "{}");

        initPivotDateRange(payload.startDate, payload.endDate, wire);
        initPivotStatus(payload.status, wire);
        renderPivotOnly(payload.pivotRows);

        Livewire.on(
            "pivotFiltersReset",
            ({ startDate, endDate, status, pivotRows }) => {
                initPivotDateRange(startDate, endDate, wire);
                initPivotStatus(status, wire);
                renderPivotOnly(pivotRows);
            },
        );
    };

    if (window.Livewire) {
        init();
    }

    document.addEventListener("livewire:init", init, { once: true });
}

window.ReportPivotPage = { boot };
