(function () {
    $.superdesk = {
        confirm: function (that_boardroom_id,callback) {
            $(".resetmask").show();
            btnOk(that_boardroom_id,callback);
            btnNo();
        }
    }

    var btnOk = function (that_boardroom_id,callback) {
        console.log("btnOk init");

        $(".resetmask .continuebtn").click(function () {
            $(".resetmask").hide();
            if(typeof (callback) == 'function'){
                callback(that_boardroom_id);
            }
        })
    };

    var btnNo = function () {
        console.log("btnNo init");
        $(".resetmask .resetbtn,.resetmask .closeicon").click(function () {
            $(".resetmask").hide();
        });
    };
})();