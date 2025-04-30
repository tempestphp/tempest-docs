<?php
/**
 * @var \App\Web\Analytics\Chart $chart
 * @var string $label
 * @var string $title
 */

use Symfony\Component\Uid\Uuid;

$uuid = Uuid::v4()->toString();
?>

<x-component name="x-chart">
    <div class="grid gap-2">
        <h2 class="font-bold">{{ $title }}</h2>
        <canvas id="<?= $uuid ?>"></canvas>

        <script>
            var ctx = document.getElementById('<?= $uuid ?>');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($chart->labels->values()->toArray()) ?>,
                    datasets: [{
                        label: '<?= $label ?>',
                        data: <?= json_encode($chart->values->values()->toArray()) ?>,
                        borderWidth: 2
                    }]
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.4
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</x-component>
