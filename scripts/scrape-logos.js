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
                    homeTeam: match.querySelector(".event__homeParticipant")?.innerText || "",
                    homeTeamLogo: match.querySelector(".event__homeParticipant img")?.src || "",
                    awayTeam: match.querySelector(".event__awayParticipant")?.innerText || "",
                    awayTeamLogo: match.querySelector(".event__awayParticipant img")?.src || "",
                }));
            });

            results.push({
                league: league.name,
                type,
                matches: leagueData
            });

            for (const match of leagueData) {
                console.log(`Enviando logo do time da casa: ${match.homeTeam} - Logo URL: ${match.homeTeamLogo}`);
                if (match.homeTeamLogo && match.homeTeam) {
                    await axios.post("http://localhost:8000/api/salvar-logos", {
                        teamName: match.homeTeam,
                        leagueName: league.name,
                        logoUrl: match.homeTeamLogo
                    }).catch(err => console.error(`Erro ao salvar logo do time da casa: ${err.response?.data?.message || err.message}`));
                }

                console.log(`Enviando logo do time visitante: ${match.awayTeam} - Logo URL: ${match.awayTeamLogo}`);
                if (match.awayTeamLogo && match.awayTeam) {
                    await axios.post("http://localhost:8000/api/salvar-logos", {
                        teamName: match.awayTeam,
                        leagueName: league.name,
                        logoUrl: match.awayTeamLogo
                    }).catch(err => console.error(`Erro ao salvar logo do time visitante: ${err.response?.data?.message || err.message}`));
                }
            }
        }
    }

    await browser.close();
})();