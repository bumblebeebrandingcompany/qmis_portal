
    // Assuming you have the dynamic lead counts for campaigns
    var campaignLeads = <?php echo json_encode($campaignLeads); ?>;
    var campaignLabels = <?php echo json_encode($campaignLabels); ?>;

    var ctxPie = document.getElementById('campaignPieChart').getContext('2d');
    // Predefined dark colors
    var darkColors = [
    'rgba(255, 234, 136, 1)', // Dark Blue
    'rgba(255, 129, 83, 1)', // Dark Red
    'rgba(74, 202, 180, 1)', // Dark Magenta
    'rgba(135, 139, 182, 1)' // Yellow (added color)
    // Add more dark colors as needed
    ];

    var darkBorderColors = [
    'rgba(0, 0, 139, 1)', // Dark Blue
    'rgba(139, 0, 0, 1)', // Dark Red
    'rgba(139, 0, 139, 1)', // Dark Magenta
    'rgba(0, 100, 0, 1)'
    // Add more dark colors as needed
    ];
  var pieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: campaignLabels,
        datasets: [{
            data: campaignLeads,
            backgroundColor: darkColors,
            borderColor: darkBorderColors,
            borderWidth: 0.5,
            barThickness: 20
        }]
    },
    options: {
        plugins: {
            datalabels: {
                color: '#fff',
                formatter: function(value, context) {
                    var label = context.chart.data.labels[context.dataIndex];
                    var leadCount = context.dataset.data[context.dataIndex];
                    return label + ' (' + leadCount + ')'; // Display label and lead count
                }
            }
        },
        tooltips: {
            enabled: false // Disable tooltips
        }
    }
});



