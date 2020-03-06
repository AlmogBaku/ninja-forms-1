function createAuthMiddleware( token ) {
	function middleware( options, next ) {

		const path = options.path || '';
		if( 0 !== path.indexOf('/ninja-forms-views/') ) return next(options)

		const { headers = {} } = options;

		// If an 'X-NinjaFormsViews-Nonce' header (or any case-insensitive variation
		// thereof) was specified, no need to add an auth header.
		for ( const headerName in headers ) {
			if ( headerName.toLowerCase() === 'x-ninjaformsviews-auth' ) {
				return next( options );
			}
		}

		return next( {
			...options,
			headers: {
				...headers,
				'X-NinjaFormsViews-Auth': middleware.token,
			},
		} );
	}

	middleware.token = token;

	return middleware;
}

export { createAuthMiddleware }