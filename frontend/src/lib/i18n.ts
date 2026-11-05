export type Locale = 'en' | 'sw';
export type Theme = 'light' | 'dark';

type Dict = Record<string, string>;

const en: Dict = {
  'brand.sub': 'Distribution',
  'grp.overview': 'Overview',
  'grp.distribution': 'Distribution',
  'grp.sales': 'Sales & Finance',
  'grp.crm': 'CRM',

  'nav.dashboard': 'Dashboard',
  'nav.orders': 'Orders',
  'nav.containers': 'Container Ledger',
  'nav.report': 'Sales Report',
  'nav.customers': 'Customers',
  'nav.invoices': 'Invoices',
  'nav.standing': 'Standing Orders',
  'nav.pipeline': 'Sales Pipeline',

  'dash.sub': 'Living Water — last 30 days',
  'kpi.revenue': 'Revenue (30 days)',
  'kpi.deliveries': 'deliveries completed',
  'kpi.receivables': 'Receivables outstanding',
  'kpi.overdue': 'invoices overdue',
  'kpi.bottles': '20L bottles in the field',
  'kpi.issued_returned': 'issued · {a} returned',
  'kpi.return_rate': 'Container return rate',
  'kpi.trucks': 'trucks on the road',
  'kpi.open_tasks': 'open follow-up tasks',

  'sec.trend': 'Sales trend — last 12 weeks',
  'sec.by_route': 'Sales by route',
  'sec.overdue_invoices': 'Overdue invoices',
  'sec.upcoming': 'Upcoming standing deliveries',
  'sec.none': 'Nothing here right now.',
  'sec.due': 'due',
  'sec.next': 'next',

  'orders.sub': 'Refill deliveries',
  'containers.sub': 'Every 20L bottle issued must come back',
  'customers.sub': 'B2B & household accounts',
  'invoices.sub': 'Bills raised to customers',
  'standing.sub': 'Recurring refill agreements',
  'pipeline.sub': 'New prospects → won accounts',
  'report.sub': 'Delivered sales for a chosen period',

  'col.order_no': 'Order #',
  'col.invoice_no': 'Invoice #',
  'col.customer': 'Customer',
  'col.route': 'Route',
  'col.ordered': 'Ordered',
  'col.delivered': 'Delivered',
  'col.status': 'Status',
  'col.total': 'Total',
  'col.business': 'Business',
  'col.type': 'Type',
  'col.tier': 'Tier',
  'col.credit': 'Credit limit',
  'col.balance': 'Balance owed',
  'col.issued': 'Issued',
  'col.returned': 'Returned',
  'col.outstanding': 'Outstanding',
  'col.deposit': 'Deposit held',
  'col.last_move': 'Last movement',
  'col.issue_date': 'Issued',
  'col.due_date': 'Due',
  'col.paid': 'Paid',
  'col.frequency': 'Frequency',
  'col.next_delivery': 'Next delivery',
  'col.active': 'Active',
  'col.bottles': 'Bottles / cycle',
  'col.date': 'Date',

  'f.status': 'Status',
  'f.all_statuses': 'All statuses',
  'f.search_order': 'Search order #',
  'f.apply': 'Apply',
  'f.reset': 'Reset',
  'f.from': 'From',
  'f.to': 'To',
  'f.generate': 'Generate',
  'f.download_csv': 'Download CSV',
  'f.print': 'Print / Save as PDF',

  'rep.deliveries': 'Deliveries',
  'rep.total_revenue': 'Total revenue',
  'rep.avg_order': 'Average order',
  'rep.none': 'No deliveries in this period.',
  'rep.total_line': 'Total',

  'ui.theme': 'Theme',
  'ui.language': 'Language',
  'misc.none': '—',
  'misc.yes': 'Active',
  'misc.no': 'Paused',
};

const sw: Dict = {
  'brand.sub': 'Usambazaji',
  'grp.overview': 'Muhtasari',
  'grp.distribution': 'Usambazaji',
  'grp.sales': 'Mauzo na Fedha',
  'grp.crm': 'CRM',

  'nav.dashboard': 'Dashibodi',
  'nav.orders': 'Oda',
  'nav.containers': 'Leja ya Madumu',
  'nav.report': 'Ripoti ya Mauzo',
  'nav.customers': 'Wateja',
  'nav.invoices': 'Ankara',
  'nav.standing': 'Oda za Kudumu',
  'nav.pipeline': 'Mchakato wa Mauzo',

  'dash.sub': 'Living Water — siku 30 zilizopita',
  'kpi.revenue': 'Mapato (siku 30)',
  'kpi.deliveries': 'utoaji uliokamilika',
  'kpi.receivables': 'Madeni yanayodaiwa',
  'kpi.overdue': 'ankara zilizochelewa',
  'kpi.bottles': 'Madumu 20L yaliyoko kwa wateja',
  'kpi.issued_returned': 'yaliyotolewa · {a} yaliyorudishwa',
  'kpi.return_rate': 'Kiwango cha kurudishwa',
  'kpi.trucks': 'malori barabarani',
  'kpi.open_tasks': 'kazi za ufuatiliaji',

  'sec.trend': 'Mwenendo wa mauzo — wiki 12',
  'sec.by_route': 'Mauzo kwa njia',
  'sec.overdue_invoices': 'Ankara zilizochelewa',
  'sec.upcoming': 'Utoaji wa kudumu unaokuja',
  'sec.none': 'Hakuna kitu kwa sasa.',
  'sec.due': 'mwisho',
  'sec.next': 'ijayo',

  'orders.sub': 'Utoaji wa kujaza upya',
  'containers.sub': 'Kila dumu 20L lililotolewa lazima lirudi',
  'customers.sub': 'Akaunti za biashara na nyumbani',
  'invoices.sub': 'Ankara zilizotolewa kwa wateja',
  'standing.sub': 'Mikataba ya kujaza upya ya kudumu',
  'pipeline.sub': 'Watarajiwa wapya → wateja walioshinda',
  'report.sub': 'Mauzo yaliyotolewa kwa kipindi uliochagua',

  'col.order_no': 'Oda #',
  'col.invoice_no': 'Ankara #',
  'col.customer': 'Mteja',
  'col.route': 'Njia',
  'col.ordered': 'Iliagizwa',
  'col.delivered': 'Ilitolewa',
  'col.status': 'Hali',
  'col.total': 'Jumla',
  'col.business': 'Biashara',
  'col.type': 'Aina',
  'col.tier': 'Daraja',
  'col.credit': 'Kikomo cha mkopo',
  'col.balance': 'Deni',
  'col.issued': 'Yaliyotolewa',
  'col.returned': 'Yaliyorudishwa',
  'col.outstanding': 'Yaliyobaki',
  'col.deposit': 'Amana iliyoshikiliwa',
  'col.last_move': 'Mwendo wa mwisho',
  'col.issue_date': 'Ilitolewa',
  'col.due_date': 'Mwisho',
  'col.paid': 'Imelipwa',
  'col.frequency': 'Marudio',
  'col.next_delivery': 'Utoaji ujao',
  'col.active': 'Inatumika',
  'col.bottles': 'Madumu / mzunguko',
  'col.date': 'Tarehe',

  'f.status': 'Hali',
  'f.all_statuses': 'Hali zote',
  'f.search_order': 'Tafuta oda #',
  'f.apply': 'Tumia',
  'f.reset': 'Weka upya',
  'f.from': 'Kutoka',
  'f.to': 'Hadi',
  'f.generate': 'Tengeneza',
  'f.download_csv': 'Pakua CSV',
  'f.print': 'Chapisha / Hifadhi PDF',

  'rep.deliveries': 'Utoaji',
  'rep.total_revenue': 'Jumla ya mapato',
  'rep.avg_order': 'Wastani wa oda',
  'rep.none': 'Hakuna utoaji katika kipindi hiki.',
  'rep.total_line': 'Jumla',

  'ui.theme': 'Mandhari',
  'ui.language': 'Lugha',
  'misc.none': '—',
  'misc.yes': 'Inatumika',
  'misc.no': 'Imesimamishwa',
};

const dicts: Record<Locale, Dict> = { en, sw };

export function makeT(locale: Locale) {
  const d = dicts[locale] ?? en;
  return (key: string, vars?: Record<string, string | number>): string => {
    let s = d[key] ?? en[key] ?? key;
    if (vars) for (const [k, v] of Object.entries(vars)) s = s.replace(`{${k}}`, String(v));
    return s;
  };
}

export function getLocale(astro: { cookies: { get: (n: string) => { value?: string } | undefined } }): Locale {
  return astro.cookies.get('lang')?.value === 'sw' ? 'sw' : 'en';
}

export function getTheme(astro: { cookies: { get: (n: string) => { value?: string } | undefined } }): Theme {
  return astro.cookies.get('theme')?.value === 'dark' ? 'dark' : 'light';
}
