export const API_BASE =
  import.meta.env.PUBLIC_API_BASE ?? 'http://127.0.0.1:8000/api';

/** Server-side fetch of a JSON endpoint from the Laravel API. */
export async function api<T = any>(path: string): Promise<T> {
  const res = await fetch(`${API_BASE}${path}`, {
    headers: { Accept: 'application/json' },
  });
  if (!res.ok) {
    throw new Error(`API ${path} failed: ${res.status}`);
  }
  return res.json() as Promise<T>;
}

export const money = (n: number | string): string =>
  'TZS ' + Math.round(Number(n)).toLocaleString('en-US');

export const num = (n: number | string): string =>
  Number(n).toLocaleString('en-US');

export const shortDate = (d: string | null): string =>
  d
    ? new Date(d).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
      })
    : '—';
