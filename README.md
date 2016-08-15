# web-3-punkt-0
beamonpeople.se 3.0

Plugin för Project, Colleagues, News samt Expertises fungerar både på startsidan och på egna sidor genom en ny widget area, "page content". Man behöver alltså lägga till dessa delvis på widget area Page content och på en widget area av typen Full footer width som endast ska synnas på start sidan. I admin gränssnittet kan man specificera för vilken sida pluginen ska synnas.
Projekt och kollegor hämtar nu information från varsin csv fil. Det är tänkt så att dessa ska hämtas dynamiskt från ett Google spreadsheet.
Nyheter under "Aktuellt", jobb annonser under "Jobb", kompetens beskrivningar under "Vad vi gör" samt konsulten har ordet är alla av typen inlägg. När webbredaktören skapar ett nytt inlägg så finns kategorierna News, Jobs, Expertises samt Consultant valbara (dessa måste man klicka upp manuellt i nuläget). Beroende på kategori som inlägget har är det synligt på olika platser.
Lite att fixa:
- Widget admin settings dynamiska beroende på vilken widget area som widgeten är placerad i
- Viss text/länkar är i nuläget statiska
- Responsiv design
- Valbar bild i jobb inlägg
- Sidomeny i nyheterna så man enkelt kan gå till annan nyhet
- Välja Beam/Projekt att läsa om på Beamon People/Projekt sidan med hjälp av Knockout.js?
- Visa bild för inläggen på sidan Vad vi gör
- Om oss sidan är tom i nuläget
- Ändra Konsulten har ordet enligt kravbeskrivning
- Se kravbeskrivning

