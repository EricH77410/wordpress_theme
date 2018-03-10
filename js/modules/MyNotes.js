import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }

    events() {
        $('#my-notes').on('click' ,'.delete-note' ,this.deleteNote.bind(this)); // on rajoute '.delete-note' pour que les nouveau post puissent être traité par l'event
        $('#my-notes').on('click', '.edit-note',this.editNote.bind(this));
        $('#my-notes').on('click', '.update-note',this.updateNote.bind(this));
        $('.submit-note').on('click', this.createNote.bind(this));
    }

    // Methods will go here
    deleteNote(e) {
        var note = $(e.target).parents('li');
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce',universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/'+note.data('id'),
            type: 'DELETE',
            success: (res) => {
                note.slideUp();
                if (res.noteCount <= 5) {
                    $('.note-limit-message').removeClass('active');
                }
            },
            error: (err) => {
                console.log('Delete failed');
                console.log(err);
            }
        })
    }

    createNote(e) {
        var newNote = {
            'title': $('.new-note-title').val(),
            'content': $('.new-note-body').val(),
            'status': 'publish'
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce',universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/',
            type: 'POST',
            data: newNote,
            success: (res) => {
                console.log(res);
                $('.new-note-title, .new-note-body').val('');
                $(`
                <li data-id="${res.id}">
                    <input readonly class="note-title-field" type="text" value="${res.title.raw}">
                    <span class="edit-note"><i class="fa fa-pencil" area-hidden="true"></i> Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" area-hidden="true"></i> Delete</span>
                    <textarea readonly class="note-body-field">${res.content.raw}</textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" area-hidden="true"></i> Save</span>
                </li>
                `).prependTo('#my-notes').hide().slideDown();
                
            },
            error: (err) => {
                console.log('Created note failed');
                if (err.responseText == 'You have reached your note limit !'){
                    $('.note-limit-message').addClass('active');
                }                
            }
        });
    }

    editNote(e) {
        var note = $(e.target).parents("li");
        if (note.data('state')=='editable') {
            this.makeNoteReadOnly(note);
        } else {
            this.makeNoteEditable(note);
        }
    }

    makeNoteEditable(note) {
        note.find('.edit-note').html('<i class="fa fa-times" area-hidden="true"></i> Cancel')
        note.find('.note-title-field, .note-body-field').removeAttr('readonly').addClass('note-active-field');
        note.find('.update-note').addClass('update-note--visible');
        note.data('state','editable');
    }

    makeNoteReadOnly(note) {
        note.find('.edit-note').html('<i class="fa fa-pencil" area-hidden="true"></i> Edit')
        note.find('.note-title-field, .note-body-field').attr('readonly','readonly').removeClass('note-active-field');
        note.find('.update-note').removeClass('update-note--visible');
        note.data('state','cancel');
    }

    updateNote(e) {
        var note = $(e.target).parents('li');
        var updatedNote = {
            'title': note.find('.note-title-field').val(),
            'content': note.find('.note-body-field').val()
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce',universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/'+note.data('id'),
            type: 'POST',
            data: updatedNote,
            success: (res) => {
                this.makeNoteReadOnly(note);
            },
            error: (err) => {
                console.log('Delete failed');
                console.log(err);
            }
        });
    }
}

export default MyNotes;