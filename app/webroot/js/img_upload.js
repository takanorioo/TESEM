        $(document).ready(function() {
            // 編集・削除キーのデフォルト値
            var key = Math.floor(Math.random() * 9999);
            $('#updelkey').attr('value', key);

            // 画像プレビュー
            $('.image_preview').change(function() {
                var file = $(this).prop('files')[0];
                var fr = new FileReader();
                fr.onload = function() {
                    $('#preview').attr('src', fr.result);   // 読み込んだ画像データをsrcにセット
                }
                fr.readAsDataURL(file);  // 画像読み込み
            });
        });