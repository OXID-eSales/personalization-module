function oeEcondaOptIn() {
    econda.privacyprotection.applyAndStoreNewPrivacySettings(
        { },
        {
            "permissions:profile": {
                state: "ALLOW"
            }
        }
    );
}

function oeEcondaOptOut() {
    econda.privacyprotection.applyAndStoreNewPrivacySettings(
        { },
        {
            "permissions:profile": {
                state: "DENY"
            }
        }
    );
}

$(document).ready(function() {
    $('#cookieNote .oeeconda-optout').on('click', function() {
        oeEcondaOptOut();
    });
    $('#cookieNote .oeeconda-optin').on('click', function() {
        oeEcondaOptIn();
    });
    if ($('#oeeconda-update').length) {
        switch (econda.privacyprotection.getPermissionsFromLocalStorage().profile.state) {
            case 'ALLOW':
                $('#oeeconda-update input[value="ALLOW"]').attr('checked', true);
                break;
            case 'DENY':
                $('#oeeconda-update input[value="DENY"]').attr('checked', true);
                break;
        }
        $('#oeeconda-update button').on('click', function() {
            switch($('#oeeconda-update input:checked').val()) {
                case 'ALLOW':
                    oeEcondaOptIn();
                    break;
                case 'DENY':
                    oeEcondaOptOut();
                    break;
            }
        });
    }
});
