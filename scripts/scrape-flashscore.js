import { launch } from 'puppeteer';
import axios from 'axios';

(async () => {
    const browser = await launch({ headless: true });
    const page = await browser.newPage();

    await page.setUserAgent(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    );

        // { name: "Dominica Premier League", url: "https://www.flashscore.com/football/dominica/premier-league/fixtures/" },
        // { name: "Rwanda National League", url: "https://www.flashscore.com/football/rwanda/national-league/fixtures/" },
        // { name: "Tanzania Premier League", url: "https://www.flashscore.com/football/tanzania/premier-league/fixtures/" },

    const leagues = [
        {
            name: "Kenya Super League",
            urlFixtures: "https://www.flashscore.com/football/kenya/super-league/fixtures/",
            urlResults: "https://www.flashscore.com/football/kenya/super-league/results/"
        },
        {
            name: "Malawi Super League",
            urlFixtures: "https://www.flashscore.com/football/malawi/super-league/fixtures/",
            urlResults: "https://www.flashscore.com/football/malawi/super-league/results/"
        },
        {
            name: "Uganda Premier League",
            urlFixtures: "https://www.flashscore.com/football/uganda/premier-league/fixtures/",
            urlResults: "https://www.flashscore.com/football/uganda/premier-league/results/"
        },
        {
            name: "Fufa Big League",
            urlFixtures: "https://www.flashscore.com/football/uganda/big-league/fixtures/",
            urlResults: "https://www.flashscore.com/football/uganda/big-league/results/"
        }
    ];

    const results = [];

    for (const league of leagues) {
        for (const type of ["fixtures", "results"]) {
            const url = type === "fixtures" ? league.urlFixtures : league.urlResults;

            console.log(`Acessando ${league.name} (${type})...`);
            await page.goto(url, { waitUntil: "networkidle2" });

            await page.waitForSelector(".event__match", { timeout: 20000 });

            const leagueData = await page.evaluate(() => {
                const matches = Array.from(document.querySelectorAll(".event__match"));

                return matches.map(match => ({
                    date: match.querySelector(".event__time")?.innerText || "",
                    // round: match.querySelector(".event__round")?.innerText || "",
                    homeTeam: match.querySelector(".event__homeParticipant")?.innerText || "",
                    awayTeam: match.querySelector(".event__awayParticipant")?.innerText || "",
                    scoreHome: match.querySelector(".event__score--home")?.innerText || null,
                    scoreAway: match.querySelector(".event__score--away")?.innerText || null
                }));
            });

            results.push({
                league: league.name,
                type,
                matches: leagueData
            });

            await axios.post("http://localhost:8000/api/salvar-partidas", {
                league: league.name,
                type,
                matches: leagueData
            }).catch(err => console.error(`Erro ao enviar os dados: ${err.response?.data?.message || err.message}`));
        }
    }

    // console.log(JSON.stringify(results, null, 2));
    await browser.close();
})();