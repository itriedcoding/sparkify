interface HelloPageProps {
	params: { name: string }
}

async function fetchGreeting(name: string) {
	const res = await fetch(`/api/v1/hello/${encodeURIComponent(name)}`, { cache: 'no-store' });
	if (!res.ok) throw new Error('Failed to fetch');
	return res.json() as Promise<{ message: string, client_ip: string | null }>;
}

export default async function HelloPage({ params }: HelloPageProps) {
	const data = await fetchGreeting(params.name);
	return (
		<main className="space-y-6">
			<h1 className="text-2xl font-semibold">Greeting</h1>
			<p className="text-lg">{data.message}</p>
			<p className="text-sm text-gray-500">Your IP: {data.client_ip ?? 'unknown'}</p>
		</main>
	);
}