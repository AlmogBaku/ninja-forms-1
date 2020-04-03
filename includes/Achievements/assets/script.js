document.addEventListener("DOMContentLoaded", function(event) {
    const notices = ninjaFormsNotifications.notices || []

    notices.forEach(function(noticeId) {
        const notice = document.getElementById(noticeId)
        const dismiss = notice.querySelector('.notice-dismiss')        
        dismiss.addEventListener('click', function() {
            jQuery.post( ajaxurl, {
                    'action': 'ninja_forms_dismiss_notification',
                    'noticeId': noticeId,
                    '_wpnonce': ninjaFormsAchievements.dismissNonce,
            });
        })
    })
})
