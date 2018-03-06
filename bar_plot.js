// tissue = document.getElementById('tissue');
// expression = document.getElementById('expression');
// var expression = [28.74, 24.805, 16.42, 16.175, 15.92, 15.23, 14.66, 12.91, 12.89, 12.79, 11.5, 8.472, 7.257, 7.123, 6.491, 6.418, 6.118, 5.373, 5.3695, 4.7525, 4.727, 4.548, 4.488, 4.4525, 4.2725, 4.1585, 3.853, 3.844, 3.821, 3.407, 3.2265, 3.137, 2.9415, 2.892, 2.478, 2.416, 2.392, 2.368, 2.366, 2.304, 2.146, 1.937, 1.7725, 1.7355, 1.5975, 1.576, 1.5275, 1.464, 1.393, 1.181, 0.6181, 0.5503, 0.4925];

var trace1 = {
  x: tissue,
  y: expression,
  type: 'bar',
  marker: {
    color: 'rgb(158,202,225)',
    opacity: 0.6,
    line: {
      color: 'rbg(8,48,107)',
      width: 1.5
    }
  }
};

var data = [trace1];

var layout = {
  title: 'Tissue Expression',
  titlefont: {
      family: 'Arial, sans-serif',
      size: 24
    },
  height: 800,
  width:1000,
  yaxis: {
    title: 'Expression (TPM)',
    titlefont: {
      family: 'Arial, sans-serif',
      size: 18
    }
  },
  margin: {
     r: 10,
     t: 100,
     b: 300,
     l: 100
   }
};

Plotly.newPlot(myBar, data, layout);
