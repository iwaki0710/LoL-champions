document.addEventListener('DOMContentLoaded', function () {
    // フィルターのチェックボックスを取得
    const filterCheckboxes = document.querySelectorAll('input[name="item-filter"]');
    // 【修正点】アイテムカードではなく、外側のリンク(a.filterable-item)を全て取得
    const itemLinks = document.querySelectorAll('.filterable-item');

    // チェックボックスの状態が変わった時に実行する関数
    function updateItemVisibility() {
        const checkedFilters = Array.from(filterCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        // 各アイテムリンクをループ
        itemLinks.forEach(link => {
            // リンクのdata-tags属性からタグの配列を取得
            const itemTags = JSON.parse(link.dataset.tags || '[]');

            // チェックされたフィルターが一つもない場合は、全てのリンクを表示
            if (checkedFilters.length === 0) {
                link.style.display = ''; // 表示
                return;
            }

            // チェックされたフィルターが、アイテムのタグに全て含まれているか確認
            const isMatch = checkedFilters.every(filter => itemTags.includes(filter));

            // 結果に応じて表示/非表示を切り替え
            if (isMatch) {
                link.style.display = ''; // 表示
            } else {
                link.style.display = 'none'; // 非表示
            }
        });
    }

    // 各チェックボックスにイベントリスナーを設定
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateItemVisibility);
    });
});
