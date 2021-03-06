var CenterX = 300;
var CenterY = 300;
const bigRadius = 250;
var colorRayAndCircleByLabel = '#48D1CC';
var colorLabel = '#36c';
var radiusLabel = 15;
var colorSelectLabel = "Red";
var shadowLabelSize = 10;
var shadowColor = "white";

/*
 * General functions
 * */

/**
 * From dec in polar
 *
 * @param {float} x
 * @param {float} y
 * @returns {object}
 */
function cartesian2Polar(x, y) {
  var upX = (x-CenterX);
  var upY = (y-CenterY);
  distance = Math.sqrt(upX * upX + upY * upY);
  radians = Math.atan2(upY, upX);
  degr = radians*180/Math.PI+90;
  polarCoor = {distance: distance, degr: degr};
  return polarCoor;
}

/**
 * From polar in dec
 *
 * @param {float} radius
 * @param {float} degr
 * @returns {object}
 */
function cartesian2Dec(radius, degr) {
  radians = (degr-90)*(Math.PI/180);
  if(degr >= 0 && degr <= 180){
    tan  = Math.tan(radians);
    x = Math.sqrt((Math.pow(radius,2))/(Math.pow(tan,2)+1));
    y = x*tan;
  }else{
    tan  = Math.tan(-radians);
    x = -Math.sqrt((Math.pow(radius,2))/(Math.pow(tan,2)+1));
    y = -x*tan;
  }
  decCoor = {X: x+CenterX, Y: y+CenterY};
  return decCoor;
}

/**
 * From polar in dec
 *
 * @param {float} radius
 * @param {float} degr
 * @returns {object}
 */
function cartesian2DecForBorder(radius, degr) {
  var newDegr = (degr-90);
  radians = newDegr*(Math.PI/180);

  if(newDegr >= 0 && newDegr <= 180){
    tan  = Math.tan(radians);
    x = Math.sqrt((radius*radius)/(tan*tan+1));
    y = x*tan;
  }else{
    tan  = Math.tan(-radians);
    x = Math.sqrt((radius*radius)/(tan*tan+1));
    y = x*tan;
  }

  decCoor = {X: x, Y: y};

  return decCoor;
}

/**
 *function for convert HEX -> rgba
 * */

function hexInArray(h){
  var m = h.slice(1).match(/.{2}/g);
  m[0]=parseInt(m[0], 16);
  m[1]=parseInt(m[1], 16);
  m[2]=parseInt(m[2], 16);
  return m;
};

function hexArrayInRgbString(m) {
  var rgb = 'rgb('+m[0]+', '+m[1]+', '+m[2]+')';
  return rgb;
}

function changeColorLayers(color,numLayers) {
  var arColor = hexInArray(color);
  var tempColor = arColor;
  var arRBA = [];
  var i = 0;
  var difColorRed = (256-arColor[0])/numLayers;
  var difColorGreen = (arColor[1])/(numLayers-1);
  var difColorBlue = (arColor[2])/(numLayers-1);
  var red = arColor[0] + difColorRed;
  var green = arColor[1];
  var blue = arColor[2];
  for(red; red <= 256.01; red = red + difColorRed){
    tempColor[0] = Math.floor(red);
    tempColor[1] = Math.floor(green);
    tempColor[2] = Math.floor(blue);
    arRBA[i] = hexArrayInRgbString(tempColor);
    green = green - difColorGreen;
    blue = blue - difColorBlue;
    i++;
  }
  console.log(arRBA);
  return arRBA;
}

/*
 * Block functions for sectors
 * */

function createSectorNew(sector_id, beginAngle, endAngle, circle_id, numLayers, color) {
  var i;

  var sector_id = sector_id;
  var beginAngle = beginAngle;
  var endAngle = endAngle;
  var circle_id = circle_id;
  var numLayers = numLayers;
  var color = color;

  var arColors = changeColorLayers(color,numLayers);
  var difRadius = bigRadius/numLayers;
  var radius = bigRadius;

  var nameArc = 'mainArc_'+sector_id;
  var nameSector = 'main_sector_'+sector_id;
  var nameGroup = 'sector_'+sector_id;
  var canvas = $('canvas');

  for(i=1;i<=numLayers;i++){
    canvas.drawSlice({
      layer: true,
      mask: true,
      groups: [nameGroup],
      fillStyle: arColors[i-1],
      x: CenterX, y: CenterY,
      start: beginAngle,
      end: endAngle,
      radius: radius,
      strokeStyle: '#f60',
      strokeWidth: 3,
    }).restoreCanvas({
      layer: true
    });

    radius = radius - difRadius;
  }

  canvas.drawSlice({
    layer: true,
    mask: true,
    x: CenterX, y: CenterY,
    start: beginAngle,
    end: endAngle,
    name: nameSector,
    groups: [nameGroup],
    circle_id: circle_id,
    sector_id: sector_id,
    radius: bigRadius,
    numLayers : numLayers,
    color : color,
    dblclick: function(layer) {
      var polar = cartesian2Polar(layer.eventX, layer.eventY);
      var link = $('#create_label_link').attr('href','/app_dev.php/notes/new/'+layer.circle_id+'?radius='+polar.distance/bigRadius+'&degr='+polar.degr);
      link.removeClass( "btn-primary" ).addClass( "btn-danger" );
      link.text('Добавить заметку в выбрнный сектор');
    },
    click: function(layer) {
      $('canvas').drawArc({
        shadowBlur: 40,
        shadowColor: 'white',
        strokeStyle: 'white',
        name: nameArc,
        groups: [nameGroup],
        strokeWidth: 3,
        x: CenterX, y: CenterY,
        radius: bigRadius,
        start: beginAngle,
        end: endAngle,
      }).restoreCanvas({
        layer: true
      });
    },
    mouseout: function(layer) {
      $('canvas').setLayer(nameArc, {
        shadowBlur: 0
      }).drawLayer();
    }
  });

  canvas.restoreCanvas({
    layer: true
  });
}

function createBorderSector(data) {
  var endCoord = cartesian2DecForBorder(bigRadius, data.beginAngle);
  $('canvas').drawVector({
    strokeStyle: 'white',
    strokeWidth: 4,
    x: CenterX, y: CenterY,
    a1: endCoord.X, l1: endCoord.Y
  });
}

function borderForSector(angle, sectorLeftId, sectorRightId, angleMin, angelMax) {
  var LabelCoord = cartesian2Dec(bigRadius, angle);
  var leftCoord = cartesian2Dec(bigRadius, angleMin+5);
  var rightCoord = cartesian2Dec(bigRadius, angelMax-5);
  $('canvas').drawArc({
    layer: true,
    draggable: true,
    sectorLeftId: sectorLeftId,
    sectorRightId: sectorRightId,
    name: 'border_'+sectorLeftId+'_'+sectorRightId,
    fillStyle: 'yellow',
    x: LabelCoord.X, y: LabelCoord.Y,
    radius: radiusLabel,
    circlePath: true,
    circleRadius: bigRadius,
    circleCenterX: CenterX,
    circleCenterY: CenterY,
    xMin: leftCoord.X, yMin: leftCoord.Y,
    xMax: rightCoord.X, yMax: rightCoord.Y,
    data: {'sectorLeft': sectorLeftId , 'sectorRight': sectorRightId},
    shadowColor: shadowColor,
    shadowBlur: shadowLabelSize,
    dragstop: function(layer) {
      var pol = cartesian2Polar(layer.x, layer.y);
      var sectorLeft = $('canvas').getLayer( 'main_sector_'+layer.sectorLeftId);
      var sectorRight = $('canvas').getLayer('main_sector_'+layer.sectorRightId);

      var circleId = sectorLeft.circle_id;
      var numLayers = sectorLeft.numLayers;

      var beginAngleL = sectorLeft.start;
      var colorL = sectorLeft.color;

      var endAngleR = sectorRight.end;
      var colorR = sectorRight.color;

      var newLeftSectorMinAngle = sectorLeft.start;
      var newRightSectorMinAngle = pol.degr;
      var oldLeftSectorMinAngle = sectorLeft.start;
      var oldRightSectorMinAngle = sectorRight.start;
      var coefficientLeft = (pol.degr - beginAngleL)/(sectorLeft.end - sectorLeft.start);
      var coefficientRight = (endAngleR - pol.degr)/(sectorRight.end - sectorRight.start);

      updateLabelPositionByChangingSector(layer,coefficientLeft,coefficientRight,newLeftSectorMinAngle,newRightSectorMinAngle,oldLeftSectorMinAngle,oldRightSectorMinAngle);

      $('canvas').removeLayerGroup('sector_'+sectorLeftId);
      createSectorNew(sectorLeftId,beginAngleL,pol.degr, circleId, numLayers, colorL);

      $('canvas').removeLayerGroup('sector_'+sectorRightId);
      createSectorNew(sectorRightId,pol.degr,endAngleR, circleId, numLayers, colorR);

      setHightMoveLayerToLayer();
    },
    drag: function(layer) {
      var pol = cartesian2Polar(layer.x, layer.y);

      $('canvas').drawVector({
        strokeStyle: 'white',
        strokeWidth: 4,
        x: CenterX, y: CenterY,
        a1: pol.degr, l1: pol.distance
      });
    },
    mouseover: function(layer) {
      $('canvas').drawVector({
        strokeStyle: 'white',
        strokeWidth: 4,
        x: CenterX, y: CenterY,
        a1: angle, l1: bigRadius
      });
    },
    mouseout: function(layer) {
      // var Label = $('canvas').getLayer(layer.name);
      // Label.fillStyle = colorLabel;
      // delRayNamePopUpAndCircleByLabel(layer.data.id);
    },
    dblclick: function(layer) {
      // $('#pop_label_link').css('display','block').attr('href','/app_dev.php/notes/list/'+layer.data.circleId+'/'+layer.data.id+'/');
    },
  });
}

/*
* Block functions for labels
* */

function rayAndCircleByLabel(layer,id) {
  var pol = cartesian2Polar(layer.x, layer.y);
  var dec = cartesian2Dec(bigRadius*2,pol.degr);
  $('canvas').drawArc({
    layer: true,
    strokeStyle: colorRayAndCircleByLabel,
    strokeWidth: 3,
    name: 'circleByLabel'+id,
    groups: ['circleByLabel'],
    x: CenterX, y: CenterY,
    radius: pol.distance,
  });
  $('canvas').drawLine({
    layer: true,
    strokeWidth: 3,
    name: 'lineByLabel'+id,
    groups: ['lineByLabel'],
    strokeStyle: colorRayAndCircleByLabel,
    x1: CenterX, y1: CenterY,
    x2: dec.X, y2: dec.Y,
  });
}

function createNamePopUpLabel(id,x,y,text) {
  var heightPopUp = 30;
  var widthPopUp = 150;

  $('canvas').drawRect({
    layer: true,
    fillStyle: 'white',
    strokeStyle: '#c33',
    strokeWidth: 2,
    name: 'nameLabelPopup'+id,
    groups: ['nameLabelPopup'],
    x: x + widthPopUp/2, y: y - heightPopUp/2 - 10,
    width: widthPopUp,
    height: heightPopUp,
    cornerRadius: 10
  });
  $('canvas').drawText({
    layer: true,
    name: 'nameLabelPopupText'+id,
    groups: ['nameLabelPopupText'],
    fillStyle: 'black',
    strokeWidth: 2,
    x: x + widthPopUp/2, y: y - heightPopUp/2 - 10,
    fontSize: '10pt',
    fontFamily: 'Verdana, sans-serif',
    maxWidth: widthPopUp,
    text: text
  })
}


function createLabel(data) {
  var LabelCoord = cartesian2Dec(data.radius*bigRadius, data.degr);
  // console.log(data.radius);
  $('canvas').drawArc({
    layer: true,
    draggable: true,
    groups: ['note_labels'],
    name: 'myLabel'+data.id,
    fillStyle: colorLabel,
    x: LabelCoord.X, y: LabelCoord.Y,
    radius: radiusLabel,
    data: {'id' : data.id, 'name': data.name , 'circleId': data.circleId},
    label_radius: data.radius,
    label_angle: data.degr,
    label_id: data.id,
    shadowColor: shadowColor,
    shadowBlur: shadowLabelSize,
    dragstop: function(layer) {
      var pol = cartesian2Polar(layer.x, layer.y);
      var dec = cartesian2Dec(pol.distance,pol.degr);

      layer.label_radius = pol.distance/bigRadius;
      layer.label_angle = pol.degr;
      // console.log(layer.label_radius,layer.label_angle);

      updateCoordinateLabel(layer.data.circleId,layer.data.id,pol.distance/bigRadius,pol.degr);
      delRayNamePopUpAndCircleByLabel(layer.data.id);
    },
    drag: function(layer) {
      delRayNamePopUpAndCircleByLabel(layer.data.id);
      rayAndCircleByLabel(layer,layer.data.id);
    },
    mouseover: function(layer) {
      var Label = $('canvas').getLayer(layer.name);
      Label.fillStyle = colorSelectLabel;
      delRayNamePopUpAndCircleAllLabels();
      rayAndCircleByLabel(layer,layer.data.id);
      setLinkLabelsByRadiusAndAngle(layer.label_radius,layer.label_angle, layer.label_id);
      createNamePopUpLabel(layer.data.id,layer.x,layer.y,layer.data.name);
    },
    mouseout: function(layer) {
      var Label = $('canvas').getLayer(layer.name);
      Label.fillStyle = colorLabel;
      delRayNamePopUpAndCircleByLabel(layer.data.id);
      removeLinkLabelsByRadiusAndAngle(layer.label_radius,layer.label_angle, layer.label_id);
    },
    dblclick: function(layer) {
      $('#pop_label_link').css('display','block').attr('href','/app_dev.php/notes/list/'+layer.data.circleId+'/'+layer.data.id+'/');
    },
  });
}

function setHightMoveLayerToLayer(){
  // Returns an array containing all draggable layers
  var dragLayers = $('canvas').getLayers(function(layer) {
    return (layer.draggable === true);
  });

  function setMoveLayerToLayer(layer, index, array) {
    $('canvas').moveLayer(layer.name, 100);
  }
  dragLayers.forEach(setMoveLayerToLayer);
}

function setLinkLabelsByRadiusAndAngle(radius, angle, label_id){
  var labels = $('canvas').getLayerGroup('note_labels');
  var radiusBorderMin = radius - 0.05;
  var radiusBorderMax = radius + 0.05;
  var angleBorderMin = angle - 10;
  var angleBorderMax = angle + 10;
  // console.log(radiusBorderMin,radiusBorderMax,angleBorderMin,angleBorderMax);

  function setFillStyleToLayer(layer, index, array) {
    if(layer.id !== label_id){
      if((layer.label_angle > angleBorderMin && layer.label_angle < angleBorderMax)
          || (layer.label_radius > radiusBorderMin && layer.label_radius < radiusBorderMax) ){
        // console.log(layer.label_angle,layer.label_radius);
        createNamePopUpLabel(layer.label_id,layer.x,layer.y,layer.data.name);
        layer.fillStyle = colorSelectLabel;
      }
    }
  }
  labels.forEach(setFillStyleToLayer);
}

function removeLinkLabelsByRadiusAndAngle(radius, angle, label_id){
  var labels = $('canvas').getLayerGroup('note_labels');
  var radiusBorderMin = radius - 0.03;
  var radiusBorderMax = radius + 0.03;
  var angleBorderMin = angle - 10;
  var angleBorderMax = angle + 10;
  // console.log(radiusBorderMin,radiusBorderMax,angleBorderMin,angleBorderMax);

  function deleteFillStyleToLayer(layer, index, array) {
    if(layer.label_id !== label_id){
      if((layer.label_angle > angleBorderMin && layer.label_angle < angleBorderMax)
          || (layer.label_radius > radiusBorderMin && layer.label_radius < radiusBorderMax) ){
        // console.log(layer.label_angle,layer.label_radius);
        layer.fillStyle = colorLabel;
        delNamePopUpByLabel(layer.label_id);
      }
    }
  }
  labels.forEach(deleteFillStyleToLayer);
}

function updateLabelPositionByChangingSector(border,coefficientLeft,coefficientRight,newLeftSectorMinAngle,newRightSectorMinAngle,oldLeftSectorMinAngle,oldRightSectorMinAngle) {
  var sector_left = $('canvas').getLayer('main_sector_'+border.sectorLeftId);
  var sector_right = $('canvas').getLayer('main_sector_'+border.sectorRightId);
  var labels = $('canvas').getLayerGroup('note_labels');

  if(typeof labels !== 'undefined'){
    function updateLabelPosition(label, index, array) {

      if(label.label_angle > sector_left.start && label.label_angle < sector_left.end){
        var newAngle = (label.label_angle-oldLeftSectorMinAngle)*coefficientLeft+newLeftSectorMinAngle;
        var LabelCoord = cartesian2Dec(label.label_radius*bigRadius, newAngle);
        label.x = LabelCoord.X;
        label.y = LabelCoord.Y;
        console.log(label.name,label.label_angle,newAngle,sector_left,newLeftSectorMinAngle,newRightSectorMinAngle,oldLeftSectorMinAngle,oldRightSectorMinAngle,coefficientLeft);
        label.label_angle = newAngle;
      }else if(label.label_angle > sector_right.start && label.label_angle < sector_right.end){
        var newAngle = (label.label_angle-oldRightSectorMinAngle)*coefficientRight+newRightSectorMinAngle;
        var LabelCoord = cartesian2Dec(label.label_radius*bigRadius, newAngle);
        label.x = LabelCoord.X;
        label.y = LabelCoord.Y;
        label.label_angle = newAngle;
      }
    }
    labels.forEach(updateLabelPosition);
  }

  // console.log(sector_left,sector_right);
}


function updateCoordinateLabel(circleId,labelId,radius,angle) {
  $.post(
      "/app_dev.php/circle/editLabelAjax",
      {
        circleId: circleId,
        labelId:labelId,
        radius:radius,
        angle:angle
      }).done(
      function (data) {
        console.log(data);

      })
}

function delRayNamePopUpAndCircleByLabel(id) {
  $('canvas').removeLayer('circleByLabel'+id);
  $('canvas').removeLayer('lineByLabel'+id);
  $('canvas').removeLayer('nameLabelPopup'+id);
  $('canvas').removeLayer('nameLabelPopupText'+id);
}

function delNamePopUpByLabel(id) {
  $('canvas').removeLayer('nameLabelPopup'+id);
  $('canvas').removeLayer('nameLabelPopupText'+id);
}

function delRayNamePopUpAndCircleAllLabels() {
  $('canvas').removeLayerGroup('circleByLabel');
  $('canvas').removeLayerGroup('lineByLabel');
  $('canvas').removeLayerGroup('nameLabelPopup');
  $('canvas').removeLayerGroup('nameLabelPopupText');
}