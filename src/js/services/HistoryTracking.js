

export const HistoryTracking = {
    add(action) {
        let data, unused, url;
        data = {'action': action};
        unused = "";
        url = "main.php?reqType=page&page=" ;

        switch (true) {
            case action.substring(0,4) == 'page':
                url += action.slice(4).toLowerCase();
                break;
            case action == 'logout':
                url += 'home';
                break;
            default:
                url += 'home';
                unused = 'Unknown action in HistoryTracking!';
                break;
        }
        
        history.pushState(data, unused, url);
    }
}