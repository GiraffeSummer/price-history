window.onload = () => {
    console.log(product, priceHistory,)

    const locale = 'en-NL'
    const DateFormatter = new Intl.DateTimeFormat(locale, {
        dateStyle: 'short',
        timeStyle: 'short',
        timeZone: 'Europe/Amsterdam'
    })
    const CurrencyFormatter = new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
        // roundingIncrement: 5,
        decimalSymbol: ','
    });

    const data = {
        labels: [...priceHistory.map(x => DateFormatter.format(new Date(x.created_at))), 'Now'],
        datasets: [
            {
                label: 'Price',
                data: [...priceHistory.map(x => x.price), product.price],
                fill: false,
                stepped: true,
                // https://assets.camelcamelcamel.com/live-assets/3camelizer-screen-opera-dac90089e917e46c48945d313cf8c242b87a1c80a398a12f2af4a0acdc77de9b.png
            }
        ]
    };
    const config = {
        type: 'line',
        data: data,
        options: {
            scales: {
                y: {
                    ticks: {
                        callback: (value, index, values) => CurrencyFormatter.format(value / 100)
                    }
                },
            },
            responsive: true,
            interaction: {
                intersect: false,
                axis: 'x'
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (value) => CurrencyFormatter.format(value.raw / 100),
                        title: (v) => null
                    },

                },
                title: {
                    display: true,
                    text: (ctx) => 'Price History: ' + product.name,
                }
            }
        }
    };
    const ctx = document.getElementById('myChart');
    new Chart(ctx, config);
}