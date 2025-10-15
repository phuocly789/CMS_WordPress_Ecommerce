import { ArrowUpRightIcon } from '@heroicons/react/24/outline';

const ProUpgradeSettingsMsg = ( { description, buttonText, buttonAction } ) => {
	return (
		<div className="p-2 flex justify-between items-center text-text-primary bg-flamingo-50 border border-solid border-flamingo-200 rounded-md">
			{ description }
			<span
				className="flex items-center gap-1 text-flamingo-400 font-semibold cursor-pointer"
				onKeyDown={ ( e ) => {
					if ( e.key === 'Enter' ) {
						buttonAction();
					}
				} }
				onClick={ () => buttonAction() }
				role="button"
				tabIndex="0"
			>
				{ buttonText }
				<ArrowUpRightIcon className="h-3 w-3" strokeWidth={ 2 } />
			</span>
		</div>
	);
};

export default ProUpgradeSettingsMsg;

