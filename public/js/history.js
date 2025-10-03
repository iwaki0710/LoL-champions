document.addEventListener('DOMContentLoaded', function () {
    const showMoreBtn = document.getElementById('show-more-btn');
    const matchList = document.getElementById('match-list');
    
    // ボタンが存在しない場合は、ここで処理を終了
    if (!showMoreBtn) {
        return;
    }

    let nextStart = 10; // 次に取得する開始位置
    // Bladeからdata属性として渡されたpuuidを取得
    const puuid = showMoreBtn.dataset.puuid; 

    showMoreBtn.addEventListener('click', function () {
        // ボタンを一時的に無効化し、テキストを変更
        showMoreBtn.disabled = true;
        showMoreBtn.textContent = '読み込み中...';

        // APIにリクエストを送信
        fetch(`/api/matches/${puuid}/${nextStart}`)
            .then(response => response.json())
            .then(data => {
                if (data.matches && data.matches.length > 0) {
                    // 新しい対戦履歴をHTMLに変換して追加
                    data.matches.forEach(match => {
                        const matchCardHtml = createMatchCard(match, data.version);
                        matchList.insertAdjacentHTML('beforeend', matchCardHtml);
                    });

                    // 次の開始位置を更新
                    nextStart += 10;

                    // もし取得した件数が10件未満なら、ボタンを隠す
                    if (data.matches.length < 10) {
                        showMoreBtn.style.display = 'none';
                    } else {
                        // ボタンを元に戻す
                        showMoreBtn.disabled = false;
                        showMoreBtn.textContent = 'さらに10件表示';
                    }
                } else {
                    // これ以上履歴がない場合はボタンを隠す
                    showMoreBtn.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching more matches:', error);
                showMoreBtn.textContent = '読み込みに失敗しました';
            });
    });

    // 対戦履歴カードのHTMLを生成する関数
    function createMatchCard(match, version) {
        const winClass = match.win ? 'win' : 'loss';
        const resultText = match.win ? '勝利' : '敗北';
        const duration = `${Math.floor(match.gameDuration / 60)}分 ${match.gameDuration % 60}秒`;

        // アイテムのHTMLを生成
        let itemsHtml = '';
        for (let i = 0; i < 7; i++) {
            if (match.items[i]) {
                itemsHtml += `<div class="match-item-icon"><img src="https://ddragon.leagueoflegends.com/cdn/${version}/img/item/${match.items[i]}.png" alt="Item"></div>`;
            } else {
                itemsHtml += `<div class="empty-item-slot"></div>`;
            }
        }
        
        // ここから下のHTML構造は、元のbladeファイルと完全に一致させる必要があります
        return `
            <div class="match-card ${winClass}">
                <div class="match-info">
                    <div class="match-game-mode">${match.gameMode}</div>
                    <div class="match-result ${winClass}">${resultText}</div>
                    <div class="match-duration">${duration}</div>
                </div>
                <div class="match-player-details">
                    <div class="details-top">
                        <div class="match-champion-details">
                            <div class="match-champion-icon">
                                <img src="https://ddragon.leagueoflegends.com/cdn/${version}/img/champion/${match.championName}.png" alt="${match.championName}">
                            </div>
                            <div class="spell-rune-icons">
                                <div class="icon-col">
                                    ${match.spell1_image ? `<img src="https://ddragon.leagueoflegends.com/cdn/${version}/img/spell/${match.spell1_image}">` : ''}
                                    ${match.spell2_image ? `<img src="https://ddragon.leagueoflegends.com/cdn/${version}/img/spell/${match.spell2_image}">` : ''}
                                </div>
                                <div class="icon-col">
                                    ${match.keystone_icon ? `<img class="rune-icon" src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/${match.keystone_icon}">` : ''}
                                    ${match.substyle_icon ? `<img class="rune-icon" src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/${match.substyle_icon}">` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="kda-cs-details">
                            <div class="kda-section">
                                <p class="match-kda">${match.kills} / <span class="deaths">${match.deaths}</span> / ${match.assists}</p>
                                <p class="kda-ratio">${match.kda} KDA</p>
                            </div>
                            <div class="cs-details">
                                <p>CS ${match.totalCS}</p>
                                <p class="cs-per-minute">(${match.csPerMinute}/m)</p>
                            </div>
                        </div>
                    </div>
                    <div class="details-bottom">
                        <div class="match-items">${itemsHtml}</div>
                    </div>
                </div>
                ${match.opponentChampionName ? `
                <div class="match-opponent">
                    <span>対面</span>
                    <div class="opponent-icon">
                        <img src="https://ddragon.leagueoflegends.com/cdn/${version}/img/champion/${match.opponentChampionName}.png" alt="${match.opponentChampionName}">
                    </div>
                </div>` : ''}
            </div>
        `;
    }
});