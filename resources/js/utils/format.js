export function formatarPreco(valor) {
    if (valor === null || valor === undefined) {
        return '—';
    }
    const n = Number(valor);
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(n);
}

export function formatarData(valor) {
    if (valor == null || valor === '') {
        return '—';
    }
    const [ano, mes, dia] = String(valor).slice(0, 10).split('-');
    if (!ano || !mes || !dia) {
        return '—';
    }
    const dt = new Date(Number(ano), Number(mes) - 1, Number(dia));
    return dt.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
}
