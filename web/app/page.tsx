async function fetchHealth() {
	const res = await fetch('/api/health', { cache: 'no-store' });
	if (!res.ok) throw new Error('Failed to fetch');
	return res.json();
}

export default async function HomePage() {
	const health = await fetchHealth();
	return (
		<main className="space-y-6">
			<h1 className="text-3xl font-bold">Coreon + Next.js</h1>
			<div className="rounded border bg-white p-4 shadow">
				<pre className="text-sm overflow-x-auto">{JSON.stringify(health, null, 2)}</pre>
			</div>
		</main>
	);
}