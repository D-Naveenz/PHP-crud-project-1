const role_text = "User: ";
let role = "N/A";

function changeModal() {
    // setting attributes
    $('.modal-title').text(role + ' Login');
    $('input[name="userid"]').attr('placeholder', role + ' ID');

    // show/hide elements
    if (role === "Staff") $('#sec_role').show();
    else $('#sec_role').hide();
    if (role === "Patient") {
        $('#sec_pass').hide();
        $('#sec_pass input').attr('required', false);
    }
    else {
        $('#sec_pass').show();
        $('#sec_pass input').attr('required', true);
    }
}

function detectRole(id_field) {
    if (id_field.length < 2 && id_field.length > 6) {
        return id_field;
    }
    else if (id_field.match(/^EN[0-9]+$/)) {
        return "Non-medical";
    }
    else if (id_field.match(/^EM[0-9]+$/)) {
        return "Medical";
    }
    else {
        return "Unknown";
    }
}

$(document).ready(() => {
    $('#modalLogin').on('shown.bs.modal', () => {
        $("input[name='role']").val(role);

        if (role === "Staff") {
            // Register role detecting event
            $('input[name="userid"]').on('input', () => {
                $('#lblRole').html(role_text + "<b>" + detectRole($("input[name='userid']").val()) + "</b>");
            });
        }
    });

    $('#modalLogin').on('hidden.bs.modal', () => {
        $('#lblRole').unbind();
    });

    $('#btnPatient').click(() => {
        role = "Patient";
        $('.avatar img').attr('src', './res/guest.png');
        changeModal();
    });

    $('#btnStaff').click(() => {
        role = "Staff";
        $('.avatar img').attr('src', './res/staff.png');
        changeModal();
    });

    $('#btnAdmin').click(() => {
        role = "Administrator";
        $('.avatar img').attr('src', './res/admin.png');
        changeModal();
    });
})