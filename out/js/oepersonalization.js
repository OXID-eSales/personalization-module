function oePersonalizationOptIn() {
    econda.privacyprotection.applyAndStoreNewPrivacySettings(
        { },
        {
            "permissions:profile": {
                state: "ALLOW"
            }
        }
    );
}

function oePersonalizationOptOut() {
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
    $('#cookieNote .oepersonalization-optout').on('click', function() {
        oePersonalizationOptOut();
    });
    $('#cookieNote .oepersonalization-optin').on('click', function() {
        oePersonalizationOptIn();
    });
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
