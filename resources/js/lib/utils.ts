import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatCurrency(value: string, currency: 'usd' | 'brl' | 'eur') {
    const locales = {
        usd: 'en-US',
        eur: 'de-DE',
        brl: 'pt-BR'
    }

    return new Intl.NumberFormat(locales[currency], {
        style: 'currency',
        currency: currency.toUpperCase()
    }).format(Number(value))
}
