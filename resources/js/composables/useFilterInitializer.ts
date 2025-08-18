export default function initializeFilters(store: any) {
    const url = window.location.href;
    const query = Object.fromEntries(new URLSearchParams(url.split('?')[1] || ''))

    const newFilters: Record<string, any> = {}

    for (const key of Object.keys(store.filters)) {
        if (query[key] !== undefined && query[key] !== '') {
            newFilters[key] = query[key]
        }
    }

    store.setFilters(newFilters)
}
