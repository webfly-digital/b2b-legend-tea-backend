/*Удаление карточки профиля*/
$(document).on('click', '.btn-del-profile', function (e) {
    var sessid = BX.bitrix_sessid(), profileid = e.currentTarget.dataset.profile,
        action = e.currentTarget.dataset.action,
        confirmModel = new KApp.Models.ConfirmModel({title: "Вы действительно хотите удалить эту карточку компании?"});
    var sendData = {
        sessid: sessid, id: profileid, action: action
    }
    var $profileCard = $(e.target).parents('.simple-card');
    confirmModel.confirmCallback = function () {

        $.ajax({
            url: ACTION_URL, method: 'post', data: sendData, success: function (res) {
                var notifyData = {
                    text: res.message
                };
                if (res.success) {
                    $profileCard.detach();
                    setTimeout(function () {
                        var href = location.origin + location.pathname;
                        location.href = href;
                    }, 2000);
                } else {
                    notifyData.type = "danger";
                }
                $.magnificPopup.close();
                KApp.Helpers.showNotify(notifyData, 3000, $('#profile-notifications'));
            }, error: function (err) {
                console.log(err);
            }
        });
    };
    var confirmView = new KApp.Views.ConfirmModal({model: confirmModel});

    e.preventDefault();
});