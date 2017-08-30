//Docs: http://nakupanda.github.io/bootstrap3-dialog/#available-options 

function heartbeat() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '/heartbeat');
  xhr.onreadystatechange = function(e) {
    if (xhr.status !== 200) {
      BootstrapDialog.show(dialog_config);
  }};
  xhr.send();
}

var dialog_config = {
  type: 'type-danger',
  title: '服务不可用',
  message: '请联系服务器管理员。',
  buttons: [{
    icon: 'glyphicon glyphicon-send',
    label: '重试',
    cssClass: 'btn-primary',
    autospin: true,
    autodestroy: false,
    action: function(dialogRef) {
      dialogRef.enableButtons(false);
      dialogRef.setClosable(false);
      dialogRef.getModalBody().html('Checking service hearbeat...');
      setTimeout(function () {
        clearInterval(beat);
        var beat = setInterval('hearbeat()', 5000);
        $.each(BootstrapDialog.dialogs, function(id, dialog){
          dialog.close();
        });
      }, 3000);
    }
  }, {
    label: '关闭',
    action: function(dialogRef) {
      $.each(BootstrapDialog.dialogs, function(id, dialog) {
        dialog.close();
      });
    }
  }],
  onshow: function (dialogRef) {
    $.each(BootstrapDialog.dialogs, function (id, dialog) {
      dialog.close();
    })
  }
};


var beat = setInterval('heartbeat()', 5000);
