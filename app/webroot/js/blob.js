// 参考	にしたページ
// HTML5のBlobで、動的データのダウンロードを、わずか3行で実装できた。
//   http://kurukurupapa.hatenablog.com/entry/20121006/1349522062
//
// 注意事項
// IE10ではローカルでは動作しない。
//

$(function() {

	if (typeof Blob !== "undefined") {
//		alert('このブラウザに対応しています');
	} else {
		alert('このブラウザには対応していません');
	}

	$("#content").keyup(function(){
		setBlobUrl("download", $("#content").val());
	});
    
    $("#content").keyup(); // 上で設定したkeyupイベント(setBlobUrl)を実行する

});

function setBlobUrl(id, content) {
        
	// 指定されたデータを保持するBlobを作成する。
    var blob = new Blob([ content ], { "type" : "application/x-msdownload" });

	// Aタグのhref属性にBlobオブジェクトを設定する。
    window.URL = window.URL || window.webkitURL;
    $("#" + id).attr("href", window.URL.createObjectURL(blob));
    $("#" + id).attr("download", "tmp.txt");

}