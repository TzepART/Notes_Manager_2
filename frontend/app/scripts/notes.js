function getNoteContent(noteId) {
  var host = "http://" + window.location.hostname + ':' + window.location.port;
  $.ajax({
    url: typeof isLocalDev !== "undefined" ? host + "/note/api/data.json" : host + "/note/api/"+noteId+"/",
    type: 'get',
    success:   function (data) {
      document.getElementById('note_view_title').innerHTML = data.title;
      document.getElementById('note_view_text').innerHTML = data.text;
    },
    error: function (err) {

    }
  });
}