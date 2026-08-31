(function () {
    var container = document.getElementById('post-frequency-chart');
    if (!container) {
        return;
    }

    var chartUrl = container.getAttribute('data-chart-url');
    var filterButtons = document.querySelectorAll('[data-chart-filters] button');

    function renderChart(series) {
        var maxCount = 1;
        for (var i = 0; i < series.length; i++) {
            if (series[i].count > maxCount) {
                maxCount = series[i].count;
            }
        }

        var width = 900;
        var height = 260;
        var barGap = 4;
        var barWidth = (width / series.length) - barGap;

        var svg = '<svg viewBox="0 0 ' + width + ' ' + (height + 20) + '" preserveAspectRatio="xMinYMin meet" class="post-frequency-chart-svg">';

        for (var j = 0; j < series.length; j++) {
            var point = series[j];
            var barHeight = Math.round((point.count / maxCount) * height);
            var x = j * (barWidth + barGap);
            var y = height - barHeight;

            svg += '<g class="post-frequency-bar">';
            svg += '<rect x="' + x + '" y="' + y + '" width="' + barWidth + '" height="' + barHeight + '">';
            svg += '<title>' + point.label + ': ' + point.count + '</title>';
            svg += '</rect>';
            svg += '<text x="' + (x + barWidth / 2) + '" y="' + (height + 14) + '" text-anchor="middle" class="post-frequency-bar-label">' + point.label + '</text>';
            svg += '</g>';
        }

        svg += '</svg>';

        container.innerHTML = svg;
    }

    function loadChart(period) {
        fetch(chartUrl + '?period=' + encodeURIComponent(period))
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Unexpected response status: ' + response.status);
                }
                return response.json();
            })
            .then(function (data) {
                renderChart(data.series);
            })
            .catch(function () {
                container.textContent = 'Failed to load chart data.';
            });
    }

    for (var k = 0; k < filterButtons.length; k++) {
        filterButtons[k].addEventListener('click', function (event) {
            for (var m = 0; m < filterButtons.length; m++) {
                filterButtons[m].classList.remove('active');
            }
            event.target.classList.add('active');
            loadChart(event.target.getAttribute('data-period'));
        });
    }

    loadChart('day');
})();
