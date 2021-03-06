/*
* block for creating sectors
* */

// setup an "add a tag" link
//btn btn-primary btn-block
var $addTagLink = $('<a href="#" class="btn btn-primary btn-block">Добавить категорию</a>');
var $newLinkLi = $('<div></div>').append($addTagLink);

jQuery(document).ready(function() {
  // Get the ul that holds the collection of tags
  var $collectionHolder = $('div.sectors');

  if (typeof $collectionHolder.html() !== 'undefined'){
    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

// TODO add when logic for delete sector will be ready
//         // add a delete link to all of the existing tag form li elements
//         $collectionHolder.find('li').each(function() {
//           addTagFormDeleteLink($(this));
//         });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
      // prevent the link from creating a "#" on the URL
      e.preventDefault();

      // add a new tag form (see code block below)
      addTagForm($collectionHolder, $newLinkLi);
    });
  }

});

function addTagForm($collectionHolder, $newLinkLi) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype');

  // get the new index
  var index = $collectionHolder.data('index');

  // Replace '$$name$$' in the prototype's HTML to
  // instead be a number based on how many items we have
  var newForm = prototype.replace(/__name__/g, index);

  // increase the index with one for the next item
  $collectionHolder.data('index', index + 1);

  // Display the form in the page in an li, before the "Add a tag" link li
  var $newFormLi = $(newForm);

  // also add a remove button, just for this example
  $newFormLi.append('<button class="remove-tag btn btn-danger"><i class="fa fa-remove fa-lg"></i></button>');

  $newLinkLi.before($newFormLi);

  // handle the removal, just for this example
  $('.remove-tag').click(function(e) {
    e.preventDefault();

    $(this).parent().remove();

    return false;
  });

//         // add a delete link to the new form
//         addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
  var $removeFormA = $('<a href="#">delete this tag</a>');
  $tagFormLi.append($removeFormA);

  $removeFormA.on('click', function(e) {
    // prevent the link from creating a "#" on the URL
    e.preventDefault();

    // remove the li for the tag form
    $tagFormLi.remove();
  });
}