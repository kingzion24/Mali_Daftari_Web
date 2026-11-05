import type { APIRoute } from 'astro';
import { API_BASE } from '../../lib/api';

export const prerender = false;

export const POST: APIRoute = async ({ request }) => {
  const { id, direction } = await request.json();
  const res = await fetch(`${API_BASE}/pipeline/${id}/move`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify({ direction }),
  });
  const data = await res.json();
  return new Response(JSON.stringify(data), {
    status: res.status,
    headers: { 'Content-Type': 'application/json' },
  });
};
