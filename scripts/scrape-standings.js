import { launch } from 'puppeteer';
import axios from 'axios';

(async () => {
    const browser = await launch({ headless: true });
    const page = await browser.newPage();

    await page.setUserAgent(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    );

    const leagues = [
        {
            name: "Kenya Super League",
            urlStandings: "https://www.flashscore.com/football/kenya/super-league/standings/"
        },
        {
            name: "Uganda Premier League",
            urlStandings: "https://www.flashscore.com/football/uganda/premier-league/standings/"
        },
        {
            name: "Fufa Big League",
            urlStandings: "https://www.flashscore.com/football/uganda/big-league/standings/"
        },
        {
            name: "Rwanda Premier League",
            urlStandings: "https://www.flashscore.com/football/rwanda/premier-league/standings/"
        },
        {
            name: "Malawi Super League",
            urlStandings: "https://www.flashscore.com/football/malawi/super-league/standings/"
        },
    ];

    const standings = [];

    for (const league of leagues) {
        console.log(`Acessando standings da liga ${league.name}...`);
        try {
            await page.goto(league.urlStandings, { waitUntil: "networkidle2" });

            await page.waitForSelector(".ui-table__row", { timeout: 20000 });

            const leagueStandings = await page.evaluate(() => {
                const rows = Array.from(document.querySelectorAll(".ui-table__row"));
                
                return rows.map(row => ({
                    position: row.querySelector(".table__cell--rank")?.innerText || "",
                    team: row.querySelector(".table__cell--participant")?.innerText || "",
                    played: row.querySelector(".table__cell--value:nth-child(3)")?.innerText || "",
                    wins: row.querySelector(".table__cell--value:nth-child(4)")?.innerText || "",
                    draws: row.querySelector(".table__cell--value:nth-child(5)")?.innerText || "",
                    losses: row.querySelector(".table__cell--value:nth-child(6)")?.innerText || "",
                    points: row.querySelector(".table__cell--points")?.innerText || ""
                }));
            });

            standings.push({
                league: league.name,
                standings: leagueStandings
            });

            try {
                await axios.post("http://localhost:8000/api/salvar-standings", {
                    league: league.name,
                    standings: leagueStandings
                });
                console.log({
                    league: league.name,
                    standings: leagueStandings
                });
            } catch (err) {
                console.error(`Erro ao enviar os dados: ${err.response?.data?.message || err.message}`);
            }

        } catch (err) {
            console.warn(`Erro ao processar standings da liga ${league.name}: ${err.message}`);
        }
    }

    console.log("Standings capturados:", JSON.stringify(standings, null, 2));

    await browser.close();
})();