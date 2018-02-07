function getNoteContent(noteId) {
  var host = "http://" + window.location.hostname + ':' + window.location.port;
  $.ajax({
    url: typeof isLocalDev !== "undefined" ? host + "/note/api/data.json" : host + "/note/api/"+noteId+"/",
    type: 'get',
    success:   function (data) {
      document.getElementById('note_view_title').innerHTML = data.title;
      document.getElementById('note_view_text').innerHTML = data.text;
      document.getElementById('note_edit_link').href = typeof isLocalDev !== "undefined" ? host + "/notes_edit.html" : host + "/note/view/"+data.id+"/";
    },
    error: function (err) {

    }
  });
}