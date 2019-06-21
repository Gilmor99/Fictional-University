import $ from 'jquery';

class MyNotes {
  // 1. describe and create/initiate our object
  constructor() {
    this.events();
  }

  // 2. events
  events() {
    $("#my-notes").on("click", ".delete-note", this.deleteNote);
    $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
    $(".submit-note").on("click", this.createNote.bind(this));
  }


  // 3. methods (function, action...)

  editNote(e) {
    var thisNote = $(e.target).parents("li");
    if(thisNote.data("state") == "editable") {
      this.makeNoteReadOnly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
  }


  makeNoteEditable(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
    thisNote.find(".update-note").addClass("update-note--visible");
    thisNote.data("state", "editable");
  }

  makeNoteReadOnly(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
    thisNote.find(".note-title-field, .note-body-field").attr("readonly", "redonly").removeClass("note-active-field");
    thisNote.find(".update-note").removeClass("update-note--visible");
    thisNote.data("state", "cancel");
  }

  updateNote(e) {
    var thisNote = $(e.target).parents("li");

    var ourUpdatedNote = {
      'title' : thisNote.find(".note-title-field").val(),
      'content' : thisNote.find(".note-body-field").val(),
    }

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
      type: 'POST',
      data: ourUpdatedNote,
      success: (response) => {
        this.makeNoteReadOnly(thisNote);
        console.log("Update Successed");
        console.log(response);
      },
      error: (response) => {
        console.log("Delete Failed");
        console.log(response);
      }
    });
  }

  createNote(e) {
    var ourNewNote = {
      'title' : $(".new-note-title").val(),
      'content' : $(".new-note-body").val(),
      'status' : 'publish'
    }

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/',
      type: 'POST',
      data: ourNewNote,
      success: (response) => {
        $(".new-note-title, .new-note-body").val('');
        $(`
            <li data-id="${response.id}">
              <input readonly class="note-title-field" value="${response.title.raw}">
              <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
              <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
              <textarea readonly class="note-body-field">${response.content.raw}</textarea>
              <span class="update-note btn btn--blue btn-small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
            </li>
          `).prependTo("#my-notes").hide().slideDown();
        console.log("Adding Note Successed");
        console.log(response);
      },
      error: (response) => {
        if(response.responseText == "You have reached your notes limit") {
          $(".note-limit-message").addClass("active");
        }
        console.log("Adding Note Failed");
        console.log(response);
      }
    });
  }

  deleteNote(e) {
    var thisNote = $(e.target).parents("li");
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-NONCE', universityData.nonce);
      },
      url: universityData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
      type: 'DELETE',
      success: (response) => {
        thisNote.slideUp();
        console.log("Delete Successed");
        console.log(response);
        if (response.userNoteCount < 5) {
          $('.note-limit-message').removeClass("active");
        }
      },
      error: (response) => {
        console.log("Delete Failed");
        console.log(response);
      }
    });
  }
}

export default MyNotes;
