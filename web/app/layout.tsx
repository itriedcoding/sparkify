import './globals.css';
import type { Metadata } from 'next';

export const metadata: Metadata = {
	title: 'Coreon Web',
	description: 'Next.js frontend for Coreon framework',
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
	return (
		<html lang="en">
			<body className="min-h-screen bg-gray-50 text-gray-900">
				<div className="container mx-auto px-4 py-8">
					{children}
				</div>
			</body>
		</html>
	);
}