function redirectLanePage(lane) {
    if (lane) {
        window.location.href = `/champions/${lane}`;
    } else {
        window.location.href = `/champions`;
    }
}

// スキン画像の要素をすべて取得
const skinImages = document.querySelectorAll('.skin-thumbnails img');
// モーダル関連の要素を取得
const modal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');
const captionText = document.getElementById('caption');
const closeBtn = document.getElementsByClassName('close')[0];

// 各画像にクリックイベントリスナーを追加
skinImages.forEach(img => {
    img.addEventListener('click', () => {
        modal.style.display = 'block';
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
    });
});

// 閉じるボタンにクリックイベントリスナーを追加
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
});

// モーダルの外側をクリックしたら閉じる
window.addEventListener('click', (event) => {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
});