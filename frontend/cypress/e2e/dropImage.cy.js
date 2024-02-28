/* eslint-disable no-undef */
const imageName = 'sample.jpeg'

describe('Drag and Drop a file', () => {
  it('passes', () => {
    cy.visit('/')

    cy.get('input[type=file]').invoke('attr', 'style', 'display: block').selectFile('../images/' + imageName)

    cy.get('[data-testid=cypress-img-preview]').should('have.text', imageName)
  })
})