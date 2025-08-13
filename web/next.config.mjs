/** @type {import('next').NextConfig} */
const config = {
	reactStrictMode: true,
	experimental: {
		typedRoutes: true,
	},
	rewrites: async () => {
		return [
			{
				source: '/api/:path*',
				destination: 'http://localhost:8000/api/:path*',
			},
		];
	},
};

export default config;