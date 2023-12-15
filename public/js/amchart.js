am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

/////////////////////////////////////////////
//Datos de Grafico de Dotacion total      //
/////////////////////////////////////////////

var chart = am4core.create("chartdiv", am4charts.PieChart3D);
chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

chart.data = data_torta;
chart.innerRadius = am4core.percent(40);
chart.depth = 20;
chart.legend = new am4charts.Legend();

var series = chart.series.push(new am4charts.PieSeries3D());
series.dataFields.value = "cantidad";
series.dataFields.depthValue = "cantidad";
series.dataFields.category = "genero";
series.slices.template.cornerRadius = 5;
series.colors.list = [
  am4core.color("#1193D2"),
  am4core.color("#5bc0de"),
  ];


/////////////////////////////////////////////
//Datos de Grafico de personal por unidad //
/////////////////////////////////////////////

var pie = am4core.create("piediv", am4charts.PieChart3D);
pie.hiddenState.properties.opacity = 10;
pie.data = data_dona;
pie.innerRadius = am4core.percent(40);
pie.depth = 25;

var series1 = pie.series.push(new am4charts.PieSeries3D());
series1.dataFields.value = "cantidad";
series1.dataFields.depthValue = "cantidad";
series1.dataFields.category = "dependencia";
series1.slices.template.cornerRadius = 5;
series1.colors.list = [
  am4core.color("#2eafee"),
  am4core.color("#4682b4"),
  am4core.color("#009bea"),
  am4core.color("#5bc0de"),
  am4core.color("#1193D2"),
  ];


/////////////////////////////////////////////
//    Datos de Grafico de vinculacion      //
/////////////////////////////////////////////

//Create axes
var chartt = am4core.create("barrasdiv", am4charts.XYChart3D);

// Add data
chartt.data = vinculacion;
var colorSet = new am4core.ColorSet();
        colorSet.list = ["#2eafee", "#4682b4", "#009bea", "#0072b7", "#1193D2","#2eafee", "#4682b4", "#009bea", "#0072b7", "#1193D2"].map(function(color) {
            return new am4core.color(color);
        });

    chartt.colors = colorSet;

// Create axes
let categoryAxis = chartt.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "vinculacion";
categoryAxis.renderer.labels.template.rotation = 10;
categoryAxis.renderer.labels.template.hideOversized = false;
categoryAxis.renderer.minGridDistance = 10;
categoryAxis.renderer.labels.template.horizontalCenter = "left";
categoryAxis.renderer.labels.template.verticalCenter = "middle";
categoryAxis.renderer.labels.template.fill = am4core.color("#fff");

//tooltips horizontal
categoryAxis.tooltip.label.maxWidth = 200;
categoryAxis.tooltip.label.wrap = true;

var label = categoryAxis.renderer.labels.template;
label.truncate = true;
label.maxWidth = 12;
label.tooltipText = "{category}";

let valueAxis = chartt.yAxes.push(new am4charts.ValueAxis());

// Create series
var series0 = chartt.series.push(new am4charts.ColumnSeries3D());
series0.dataFields.valueY = "cant";
series0.dataFields.categoryX = "vinculacion";
series0.name = "Vinculacion";
series0.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series0.columns.template.fillOpacity = .8;

var columnTemplate = series0.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;
columnTemplate.stroke = am4core.color("#FFFFFF");

columnTemplate.adapter.add("fill", function(fill, target) {
  return chartt.colors.getIndex(target.dataItem.index);
})

columnTemplate.adapter.add("stroke", function(stroke, target) {
  return chartt.colors.getIndex(target.dataItem.index);
})

chartt.cursor = new am4charts.XYCursor();
chartt.cursor.lineX.strokeOpacity = 0;
chartt.cursor.lineY.strokeOpacity = 0;

/////////////////////////////////////////////
//Datos de Grafico de Nivel de Formación   //
/////////////////////////////////////////////

 // Create chart instance
  var chart1 = am4core.create("nivelessdiv", am4charts.XYChart3D);
  chart1.colors = colorSet;
// Add data
chart1.data = formacion;
  // Create axes
  var categoryAxis2 = chart1.xAxes.push(new am4charts.CategoryAxis());
  categoryAxis2.dataFields.category = "nivel";
  categoryAxis2.renderer.grid.template.location = 0;
  categoryAxis2.renderer.minGridDistance = 100;
  categoryAxis2.renderer.labels.template.fill = am4core.color("#fff");
  var valueAxis2 = chart1.yAxes.push(new am4charts.ValueAxis());

  valueAxis2.renderer.labels.template.adapter.add("text", function (text) {
    return text ;
  });

  // Create series
  var series = chart1.series.push(new am4charts.ColumnSeries3D());
  series.dataFields.valueY = "incompleto";
  series.dataFields.categoryX = "nivel";
  series.name = "Incompleto";
  series.clustered = false;
  series.columns.template.tooltipText = "{categoryX} {name} : [bold]{valueY}[/]";
  series.columns.template.fillOpacity = 0.9;

  var series2 = chart1.series.push(new am4charts.ColumnSeries3D());
  series2.dataFields.valueY = "completo";
  series2.dataFields.categoryX = "nivel";
  series2.name = "Completo";
  series2.clustered = false;
  series2.columns.template.tooltipText = "{categoryX} {name} : [bold]{valueY}[/]";



/////////////////////////////////////////////
//Datos de Grafico de Situacion de Genero  //
/////////////////////////////////////////////

// create chart
var chartg = am4core.create("generodiv", am4plugins_sunburst.Sunburst);
chartg.padding(0,0,0,0);
chartg.radius = am4core.percent(98);

chartg.data = genero;
chartg.colors = colorSet;
chartg.colors.step = 2;
chartg.fontSize = 11;
chartg.innerRadius = am4core.percent(20);

// define data fields
chartg.dataFields.value = "value";
chartg.dataFields.name = "name";
chartg.dataFields.children = "children";


var level0SeriesTemplate = new am4plugins_sunburst.SunburstSeries();
level0SeriesTemplate.hiddenInLegend = false;
chartg.seriesTemplates.setKey("0", level0SeriesTemplate)

// this makes labels to be hidden if they don't fit
level0SeriesTemplate.labels.template.truncate = true;
level0SeriesTemplate.labels.template.hideOversized = true;

level0SeriesTemplate.labels.template.adapter.add("rotation", function(rotation, target) {
  target.maxWidth = target.dataItem.slice.radius - target.dataItem.slice.innerRadius - 10;
  target.maxHeight = Math.abs(target.dataItem.slice.arc * (target.dataItem.slice.innerRadius + target.dataItem.slice.radius) / 2 * am4core.math.RADIANS);

  return rotation;
})

var level1SeriesTemplate = level0SeriesTemplate.clone();
chartg.seriesTemplates.setKey("1", level1SeriesTemplate)
level1SeriesTemplate.fillOpacity = 0.75;
level1SeriesTemplate.hiddenInLegend = true;

var level2SeriesTemplate = level0SeriesTemplate.clone();
chartg.seriesTemplates.setKey("2", level2SeriesTemplate)
level2SeriesTemplate.fillOpacity = 0.5;
level2SeriesTemplate.hiddenInLegend = true;

chartg.legend = new am4charts.Legend();
  
  $('div:nth-child(2) > svg:nth-child(1) > g:nth-child(3) > g:nth-child(2) > g:nth-child(2) > g:nth-child(1) > g:nth-child(3)').remove();

}); // end am4core.ready()
