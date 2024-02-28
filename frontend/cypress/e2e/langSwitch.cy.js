/* eslint-disable no-undef */
const tDe = require('../../src/locales/de.json')

describe('Switch language to DE', () => {
  it('passes', () => {
    cy.visit('/')

    cy.get('[data-testid=cypress-lang-switch]').select('de')

    cy.get('[data-testid=cypress-max_labels]').should('have.text', tDe.max_labels)
  })
})