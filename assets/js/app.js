// define o elemento modal
const modal = document.querySelector('div.modal')
// define o corpo da modal
const modalBody = modal.querySelector('div.modal-body')

// define o elemento form
const form = document.querySelector('form')
const formSubmitButton = form.querySelector('button.primary')

// define o elemento table
const table = document.querySelector('table')

/**
 * Função para exibir detalhes em janela modal
 * @param id
 * @returns {Promise<void>}
 */
const viewEvent = async (id) => {
  const event = await getEventData(id)

  // alimenta a modal com conteúdo
  modalBody.innerHTML = `
  <h3>${event.event}</h3>
  <div>
    <h1 class='fw-light m-0'>${dateBR(event.date)}</h1>
    <small class='d-block text-primary'>${fullTextDate(event.date)}</small>
  </div>
  <small class='d-block text-secondary mt-3'>Evento criado em: ${event.created_at}</small>
  `
  modalBody.classList.add('text-center')
  // exibe a modal
  modal.style.display = 'flex'
}

/**
 * Função pra buscar evento por id
 * @param id
 * @returns {Promise<*>}
 */
const getEventData = async (id) => {
  const response = await fetchJson(`api/?id=${id}`, '', 'GET')

  // se a resposta tiver um índice error
  if (response.hasOwnProperty('error')) {
    modalBody.innerHTML = response.error
    modal.style.display = 'flex'
  } else {
    return response[0]
  }
}

/**
 * Função para preencher o form com dados para edição
 * @param id
 */
const editEvent = async (id) => {
  const event = await getEventData(id)

  const data = ['event', 'date']

  for (const index of data) {

    if (index === 'date') {
      event[index] = dateSQL(event[index])
    }

    form.querySelector(`[name="${index}"]`).value = event[index]
  }
  form.dataset.edit = id
  formSubmitButton.innerText = 'Atualizar'
}
/**
 * Função para retornar data por extenso
 * @param date
 * @param format
 * @returns {string}
 */
const fullTextDate = (date, format = 'long') => {
  return new Date(date).toLocaleDateString('pt-br', {
    month: format, day: 'numeric', weekday: format, year: 'numeric',
  })
}
// retornar data em formato br: dd/mm/aaaa
const dateBR = (date) => {
  return new Date(date).toLocaleDateString('pt-br', {
    month: 'numeric', day: 'numeric', year: 'numeric',
  })
}
// retornar data em formato br: dd/mm/aaaa
const dateSQL = (date) => {
  return new Date(date).toLocaleDateString('en-CA', {
    month: 'numeric', day: 'numeric', year: 'numeric',
  })
}
/**
 * Funçõo para submeter requests a um servidor, esperendo json como resposta
 * @param url
 * @param data
 * @param method
 * @returns {Promise<any>}
 */
const fetchJson = async (url, data, method = 'POST') => {

  const headers = {
    method: method,
  }

  if (method === 'POST') {
    headers.body = data
  }

  // faz o request
  const request = await fetch(url, headers)

  // converte o resultado da request em json
  const response = await request.json()

  // retorna a resposta
  return response
}

const generateButton = ({title, action, icon, classes = 'btn'}) => {
  return `<button class='btn ${classes}' title='Ver ${title}' onclick='${action}'>${icon}</button>`
}

/**
 * Função pra gerar linhas de uma tabela
 * @param data
 * @returns {HTMLTemplateElement}
 */
const generateTableRows = (data) => {
  // define a ordem dos dados
  const cellsOrder = ['id', 'event', 'date']

  // cria um elemento container
  const container = document.createElement('div')

  // para cada registro em data
  for (const index in data) {
    const rowData = data[index]

    // cria uma linha de tabela
    const row = document.createElement('tr')
    row.dataset.id = rowData.id

    // para cada elemento em cellsOrder
    for (const key in cellsOrder) {
      if (cellsOrder[key] === 'date') {
        rowData[cellsOrder[key]] = `${dateBR(rowData[cellsOrder[key]])}<small class='d-block text-primary'>${fullTextDate(rowData[cellsOrder[key]])}</small>`
      }

      // insere uma célula na linha de tabela
      row.insertCell(key).innerHTML = rowData[cellsOrder[key]]
    }

    // define propriedades dos botões
    const buttons = [
      // botão de visualizar
      {
        title  : `Ver ${rowData.event}`,
        classes: 'edit',
        icon   : 'V',
        action : `viewEvent("${rowData.id}")`,
      },
      // botão de editar
      {
        title  : `Editar ${rowData.event}`,
        classes: 'edit',
        icon   : 'E',
        action : `editEvent("${rowData.id}")`,
      },
      // botão de excluir
      {
        title  : `Excluir ${rowData.event}`,
        classes: 'delete',
        icon   : '&times;',
        action : `deleteEvent("${rowData.id}")`,
      },
    ]

    // insere mais uma célula para cada botão
    for (const button of buttons) {
      row.insertCell().innerHTML = generateButton(button)
    }

    container.append(row)
  }

  // retorna o conteúdo do container
  return container.innerHTML
}

modal.querySelector('.modal-header button').addEventListener('click', () => {
  modal.style.display = 'none'
})

// ao submeter o form
form.addEventListener('submit', async (e) => {
  e.preventDefault()

  // define o form
  const form = e.target

  // define os dados do form
  const fData = new FormData(form)

  if (form.hasAttribute('data-edit')) {
    fData.append('id', form.dataset.edit)
  }

  // submete os dados do form
  const response = await fetchJson(`api/${form.getAttribute('action')}.php`, fData)

  // se a resposta tiver um índice error
  if (response.hasOwnProperty('error')) {
    modalBody.innerHTML = response.error
  } else {
    modalBody.innerHTML = response.success
    // exibe a modal
    modal.style.display = 'flex'
    // atualiza a tabela
    await renderTableData()

    // reseta o form
    form.reset()
  }

})
// ao resetar o form
form.addEventListener('reset', async (e) => {
  formSubmitButton.innerText = 'Salvar'
  form.removeAttribute('data-edit')
})

// função para renderizar os dados na tabela
const renderTableData = async () => {
  // submete um request para buscar os registros
  const response = await fetchJson('api/', '', 'GET')

  // se a resposta tiver um índice error
  if (response.hasOwnProperty('error')) {
    modalBody.innerHTML = response.error
    // exibe a modal
    modal.style.display = 'flex'
  } else {
    // alimenta o tBody com os dados da resposta
    table.querySelector('tbody').innerHTML = generateTableRows(response)
  }
}


// assim q o html for carregado
document.addEventListener('DOMContentLoaded', async (e) => {
  // carrega os dados para dentro da tabela
  await renderTableData()
})
