var numLayers = 4;

var dataSector1 = {
  id: 1,
  numLayers: numLayers,
  color: '#8FBC8F',
  beginAngle: 10,
  endAngle: 90,
  name: 'Example1',
  circle_id: 1,
};

var dataSector2 = {
  id: 2,
  numLayers: numLayers,
  color: '#FFD700',
  beginAngle: 90,
  endAngle: 200,
  name: 'Example2',
  circle_id: 1,
};
var dataSector3 = {
  id: 3,
  numLayers: numLayers,
  color: '#BA55D3',
  beginAngle: 200,
  endAngle: 10,
  name: 'Example3',
  circle_id: 1,
};


createSectorNew(dataSector1.id, dataSector1.beginAngle, dataSector1.endAngle, dataSector1.circle_id, dataSector1.numLayers, dataSector1.color)
createSectorNew(dataSector2.id, dataSector2.beginAngle, dataSector2.endAngle, dataSector2.circle_id, dataSector2.numLayers, dataSector2.color)
createSectorNew(dataSector3.id, dataSector3.beginAngle, dataSector3.endAngle, dataSector3.circle_id, dataSector3.numLayers, dataSector3.color)

borderForSector(dataSector1.endAngle,dataSector1.id,dataSector2.id, dataSector1.beginAngle,dataSector2.endAngle);
borderForSector(dataSector2.endAngle,dataSector2.id,dataSector3.id, dataSector2.beginAngle,dataSector3.endAngle);
borderForSector(dataSector3.endAngle,dataSector3.id,dataSector1.id, dataSector3.beginAngle,dataSector1.endAngle);

var dataLabel1 = {
  id: 1,
  radius: 0.43,
  degr: 30,
  name: 'Note1'
};

var dataLabel2 = {
  id: 2,
  radius: 0.71,
  degr: 60,
  name: 'Note2'
};

var dataLabel3 = {
  id: 3,
  radius: 0.41,
  degr: 100,
  name: 'Note3'
};

var dataLabel4 = {
  id: 4,
  radius: 0.81,
  degr: 170,
  name: 'Note4'
};

var dataLabel5 = {
  id: 5,
  radius: 0.91,
  degr: 140,
  name: 'Note5'
};


createLabel(dataLabel1);
createLabel(dataLabel2);
createLabel(dataLabel3);
createLabel(dataLabel4);
createLabel(dataLabel5);


$(document).ready(function () {
  // $('canvas').triggerLayerEvent('myLabel1', 'mouseover');
  $('canvas').triggerLayerEvent('slice11', 'click');
});