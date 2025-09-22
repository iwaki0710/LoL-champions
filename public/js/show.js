// スキン画像の要素をすべて取得
const skinImages = document.querySelectorAll('.skin-thumbnails img');
const skinsArray = Array.from(skinImages); // NodeListを配列に変換

// モーダル関連の要素を取得
const modal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');
const captionText = document.getElementById('caption');
const closeBtn = document.getElementsByClassName('close')[0];
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
let currentImageIndex = 0;

// 各画像にクリックイベントリスナーを追加
skinImages.forEach((img, index) => {
    img.addEventListener('click', () => {
        modal.style.display = 'block';
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
        currentImageIndex = index; // 現在の画像のインデックスを保存
    });
});

// 次の画像を表示する関数
function showNextImage() {
    currentImageIndex++;
    if (currentImageIndex >= skinsArray.length) {
        currentImageIndex = 0; // 最後の画像なら最初の画像に戻る
    }
    updateModalImage();
}

// 前の画像を表示する関数
function showPrevImage() {
    currentImageIndex--;
    if (currentImageIndex < 0) {
        currentImageIndex = skinsArray.length - 1; // 最初の画像なら最後の画像に戻る
    }
    updateModalImage();
}

// モーダル内の画像を更新する関数
function updateModalImage() {
    const newImage = skinsArray[currentImageIndex];
    modalImg.src = newImage.src;
    captionText.innerHTML = newImage.alt;
}

// 矢印ボタンにクリックイベントを追加
nextBtn.addEventListener('click', showNextImage);
prevBtn.addEventListener('click', showPrevImage);

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