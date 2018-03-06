// var debug = document.getElementById("sliderAmount");
// debug.innerHTML = locations;


function log10_p(x){
  return -1 * Math.log10(x);
};

var log10_p_values2 = pvalues.map(log10_p);
var abs_beta = beta.map(Math.abs)
var upper_limit = Math.max.apply(null, log10_p_values2) + 2
var left_limit = Math.min.apply(null, locations) - 500
var right_limit = Math.max.apply(null, locations) + 500
var indeces = []
var color_plot = [];
var text_plot = [];


function size_beta(x) {
  if (x < 0.05){
    return 10;
  } else if (x < 0.10) {
    return 15;
  } else if (x < 0.20) {
    return 20;
  } else {
    return 25;
  }
}


var size_plot = abs_beta.map(size_beta);
var log10_thresh = 0;
var debug = document.getElementById("sliderAmount");

for (i in log10_p_values2) {
  if (log10_p_values2[i] > 1.30103){
    color_plot.push("green");

  }else{
    color_plot.push("red");
  }
}


for (i = 0; i < locations.length; i++){
  text_plot.push("SNP: " + snps[i] + "<br>" + "p-value: " + pvalues[i] + "<br>" + "Beta Value: " + beta[i] + "<br>");
};


var data_man = {
  x: locations,
  y: log10_p_values2,
  mode: 'markers',
  type: 'scatter',
  name: 'significant',
  text: text_plot,
  marker: {
    color: color_plot,
    size: size_plot
    },
  transforms: [{
  type: 'filter',
  target: 'y',
  operation: '>',
  value: log10_thresh
  }]
};


var layout_man = {
  hovermode: 'closest',
  xaxis: {
    title: 'Chromosome '.concat(chr,' (Mb)'),
    titlefont: {
      family: 'Arial, sans-serif',
      size: 18,

    },
    showline: true,
    range: [left_limit, right_limit],
    showticklabels: true,
    zeroline: true,
    width:1000,
    height:800,
    showgrid: false,
    tickfont: {
      family: 'Old Standard TT, serif',
      size: 14,
      color: 'black'
    },
    exponentformat: 'e',
    showexponent: 'All'
  },
  yaxis: {
    title: '-log10 (P value)',
    showline: true,
    showgrid: false,
    titlefont: {
      family: 'Arial, sans-serif',
      size: 18,
    },
    showticklabels: true,
    tickfont: {
      family: 'Old Standard TT, serif',
      size: 14,
      color: 'black'
    },
    exponentformat: 'e',
    showexponent: 'All',
    range: [0, upper_limit]
  },
  margin: {
    l: 50,
    r: 50,
    b: 100,
    t: 0,
    pad: 4
  },
  shapes: [
  {
      type: 'line',
      xref: 'paper',
      x0: 0,
      y0: 1.30103,
      x1: 1,
      y1: 1.30103,
      line:{
          color: 'grey',
          width: 1,
          dash:'dash'
        }
  }
  ]
};


var myPlot = document.getElementById('myMan');
Plotly.newPlot(myPlot, [data_man], layout_man);

myPlot.on('plotly_click', function(data_man){
  var link = "SNP_page.php?ref="
  window.open(link.concat(snps[data_man.points[0].pointNumber]), '_blank');
});



var thresh = document.getElementById("pvalue").value;


function updateSlider() {

  thresh = document.getElementById("pvalue").value;
  log10_thresh = -1 * Math.log10(thresh);
  var slider_value = document.getElementById("sliderAmount");
  slider_value.innerHTML = thresh
  var data_man = {
  x: locations,
  y: log10_p_values2,
  mode: 'markers',
  type: 'scatter',
  name: 'significant',
  text: text_plot,
  marker: {
    color: color_plot,
    size: size_plot
    },
  transforms: [{
  type: 'filter',
  target: 'y',
  operation: '>',
  value: log10_thresh
  }]
};
  var myPlot = document.getElementById('myMan');
  Plotly.newPlot(myPlot, [data_man], layout_man);
  myPlot.on('plotly_click', function(data_man){
    var link = "SNP_page.php?ref=" ;
    window.open(link.concat(snps[data_man.points[0].pointNumber]), '_blank');
  });
};


function SNPeffect(effect) {
  var debug = document.getElementById("sliderAmount");
  var indices = [];

  if (effect == "protective"){
    for (i = 0; i < beta.length; i++) {
      if (beta[i] < 0){
        indices.push(i);
      }
    }
  } else if (effect == "damaging") {
    for (i = 0; i < beta.length; i++) {
      if (beta[i] > 0){
        indices.push(i);
      }
    }
  }else if (effect == "both"){
    for (i = 0; i < beta.length; i++) {
      indices.push(i);
    }
  }
  ;
  var p_values_selected = indices.map(function(ind) {
    return log10_p_values2[ind];
  });

  var locations_selected = indices.map(function(ind) {
    return locations[ind];
  });

  var abs_beta_selected = indices.map(function(ind) {
    return size_plot[ind];
  });

  var color_selected = indices.map(function(ind) {
    return color_plot[ind];
  });

  var text_selected = indices.map(function(ind){
    return text_plot[ind];
  });

  var data_man = {
  x: locations_selected,
  y: p_values_selected,
  mode: 'markers',
  type: 'scatter',
  name: 'significant',
  text: text_selected,
  marker: {
    color: color_selected,
    size: abs_beta_selected
    },
  transforms: [{
  type: 'filter',
  target: 'y',
  operation: '>',
  value: log10_thresh
  }]
};
var myPlot = document.getElementById('myMan');
Plotly.newPlot(myPlot, [data_man], layout_man);
myPlot.on('plotly_click', function(data_man){
  var link = "SNP_page.php?ref=" ;
  window.open(link.concat(snps[data_man.points[0].pointNumber]), '_blank');
  });
};


function get_snp(){
  var snp = document.getElementById('textbox').value;
  var link = "SNP_page.php?ref=";
  window.open(link.concat(snp), '_blank');
}
