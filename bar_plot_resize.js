(function() {
var d3 = Plotly.d3;

var WIDTH_IN_PERCENT_OF_PARENT = 100,
    HEIGHT_IN_PERCENT_OF_PARENT = 100;

var gd3 = d3.select('body')
    .append('div')
    .style({
        width: WIDTH_IN_PERCENT_OF_PARENT + '%',
        'margin-left': (100 - WIDTH_IN_PERCENT_OF_PARENT) / 2 + '%',

        height: HEIGHT_IN_PERCENT_OF_PARENT + 'vh',
        'margin-top': (100 - HEIGHT_IN_PERCENT_OF_PARENT) / 2 + 'vh'
    });

var gd = gd3.node();

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
  height: 750,
  titlefont: {
      family: 'Arial, sans-serif',
      size: 24
    },
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

Plotly.plot(myBar, data, layout);

window.onresize = function() {
    Plotly.Plots.resize(myBar);
};

})();

// Plotly.newPlot(myBar, data, layout);
