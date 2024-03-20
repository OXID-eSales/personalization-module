$(document).ready(function() {
    if ($('#oepersonalization-update').length) {
        switch (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state) {
            case 'ALLOW':
                $('#oepersonalization-update input[value="ALLOW"]').attr('checked', true);
                break;
            case 'DENY':
                $('#oepersonalization-update input[value="DENY"]').attr('checked', true);
                break;
        }
        $('#oepersonalization-update button').on('click', function() {
            switch($('#oepersonalization-update input:checked').val()) {
                case 'ALLOW':
                    oePersonalizationOptIn();
                    break;
                case 'DENY':
                    oePersonalizationOptOut();
                    break;
            }
        });
    }
});
