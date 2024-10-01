document.addEventListener('DOMContentLoaded', () => {
    // 国名ボタンをクリックしたときの動作
    const countryButtons = document.querySelectorAll('.country-button');
    
    countryButtons.forEach(button => {
        button.addEventListener('click', () => {
            // ボタンに関連付けられた詳細情報の要素を取得
            const details = button.nextElementSibling;
            
            // 詳細情報が表示されているかどうかをチェック
            if (details.classList.contains('active')) {
                details.classList.remove('active'); // 詳細情報を非表示
            } else {
                // すべての詳細情報を非表示にする
                document.querySelectorAll('.country-details').forEach(detail => detail.classList.remove('active'));
                details.classList.add('active'); // 詳細情報を表示
            }
        });
    });
});
