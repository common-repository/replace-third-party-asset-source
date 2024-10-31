document.addEventListener('DOMContentLoaded', function() {
	const newReplacementButton = document.querySelector('button[data-action="addNewReplacement"]');
	const replacementList = document.querySelector('.rtpas_replacement_list');
	const removeRowButtons = document.querySelectorAll('button[data-action="removeRow"]');
	var replacementRowIndex = 0;

	if(jsonReplacementList) {
		let dbReplacementList = Object.values(jsonReplacementList);
		buildDBReplacementRow(dbReplacementList);

		function buildDBReplacementRow(dbReplacementList) {
			dbReplacementList.forEach( (list, index)=>{
				if(list.tgt != '' && list.rpl != '') {
					let values = {
						tgt: list.tgt,
						rpl: list.rpl
					}
					addNewReplacementRow(values);
				}
			})
		}
	}

	if(newReplacementButton) {
		newReplacementButton.addEventListener('click', ()=>{
			addNewReplacementRow();
		});
	}

	function addNewReplacementRow(values = null){
		const index = replacementRowIndex;

		const targetSourceDiv = document.createElement('div');
		const targetSourceInput = document.createElement('input');
		targetSourceDiv.className = 'target';
		targetSourceDiv.innerHTML = `
			<label for="${inputName}[${index}][tgt]">Target Asset Url</label>
		`;
		targetSourceInput.setAttribute( 'type', 'text' );
		targetSourceInput.setAttribute( 'name', `${inputName}[${index}][tgt]`);
		targetSourceInput.setAttribute( 'placeholder', `https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css`);
		

		const replaceSourceDiv = document.createElement('div');
		const replaceSourceInput = document.createElement('input');
		replaceSourceDiv.className = 'replacement';
		replaceSourceDiv.innerHTML = `
			<label for="${inputName}[${index}][rpl]">Replacement Asset Url</label>
		`;
		replaceSourceInput.setAttribute( 'type', 'text' );
		replaceSourceInput.setAttribute( 'name', `${inputName}[${index}][rpl]`);
		replaceSourceInput.setAttribute( 'placeholder', `https://myotherresource.domain/font-awesome.min.css`);
		
		const removeRowButton = document.createElement('button');
		removeRowButton.setAttribute( 'type', 'button' );
		removeRowButton.setAttribute( 'data-action', 'removeRow');
		removeRowButton.setAttribute( 'data-action-target', `rtpas_replacement_row_${index}`);
		removeRowButton.className = 'button-secondary';
		removeRowButton.innerText = 'Remove';

		const newRow = document.createElement('div');
		newRow.className  = 'rtpas_replacement_row';
		newRow.id  = `rtpas_replacement_row_${index}`;

		if(values) {
			if(values.tgt) {
				targetSourceInput.value = values.tgt;
			}

			if(values.rpl) {
				replaceSourceInput.value = values.rpl;
			}
		}

		targetSourceDiv.appendChild(targetSourceInput);
		replaceSourceDiv.appendChild(replaceSourceInput);

		newRow.appendChild(targetSourceDiv);
		newRow.appendChild(replaceSourceDiv);
		newRow.appendChild(removeRowButton);

		replacementList.appendChild(newRow);

		removeRowButton.addEventListener('click', (e)=>{
			removeReplacementRow(removeRowButton);
		})

		replacementRowIndex++;
	};


	function removeReplacementRow(element) {
		const targetId = element.getAttribute('data-action-target');
		if(targetId) {
			const targetRow = document.getElementById(targetId);
			if(targetRow) {
				targetRow.remove();
			}
		}		
	}

});