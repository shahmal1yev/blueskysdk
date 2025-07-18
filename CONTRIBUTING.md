# Contributing to BlueSky SDK

Thank you for your interest in contributing to the BlueSky SDK! This document provides guidelines for contributing to the project.

## Code Standards

### PSR Standards
- Follow PSR-1 and PSR-4 coding standards
- Use proper namespacing as defined in `composer.json`
- Follow PSR-12 for coding style

### Development Principles
- Apply SOLID principles in all code
- Follow DRY (Don't Repeat Yourself) methodology
- Prioritize clean architecture and maintainable code
- Use appropriate design patterns when beneficial

## Development Setup

### Prerequisites
- PHP 7.4 or higher
- Composer
- Extensions: `json`, `curl`, `fileinfo`

### Installation
```bash
git clone https://github.com/shahmal1yev/blueskysdk.git
cd blueskysdk
composer install
```

## Testing

### Running Tests
```bash
# Run all tests
composer test

# Run unit tests only
composer test-unit

# Run feature tests only
composer test-feature
```

### Test Requirements
- Write comprehensive tests for all new features
- Maintain or improve code coverage
- Follow existing test patterns and structure
- Place unit tests in `tests/Unit/`
- Place feature tests in `tests/Feature/`

## Code Quality

### Static Analysis
Run PHPStan before submitting:
```bash
composer analyse
```

### Code Structure
- Follow the established directory structure
- Use appropriate contracts and interfaces
- Implement proper error handling with custom exceptions
- Use factories for complex object creation

## Contribution Workflow

### Pull Request Process
1. Fork the repository
2. Create a feature branch from the appropriate base branch
3. Implement your changes following the coding standards
4. Write comprehensive tests
5. Run all tests and ensure they pass
6. Run static analysis and fix any issues
7. Submit a pull request with a clear description

### Branch Naming
- Feature branches: `feature/your-feature-name`
- Bug fixes: `bugfix/issue-description`
- Documentation: `docs/documentation-update`

### Commit Messages
- Use clear, descriptive commit messages
- Follow conventional commit format when possible
- Reference issue numbers when applicable

## Code Architecture

### Lexicons
- ATProto lexicons are implemented in `src/Lexicons/`
- Follow the existing pattern for new lexicon implementations
- Use proper request/response handling

### Data Models
- Implement proper data models in `src/DataModel/`
- Use appropriate field types and handlers
- Follow the established casting patterns

### Responses
- Response objects go in `src/Responses/`
- Extend `BaseResponse` for consistency
- Implement proper object mapping

## Documentation

### Code Documentation
- Use PHPDoc blocks for all public methods
- Document complex algorithms and business logic
- Keep documentation up to date with code changes

### Examples
- Provide practical usage examples
- Update existing examples when changing APIs
- Test all examples to ensure they work

## Issue Reporting

### Bug Reports
- Use the GitHub issue tracker
- Provide detailed reproduction steps
- Include PHP version and environment details
- Attach relevant error messages and stack traces

### Feature Requests
- Clearly describe the proposed feature
- Explain the use case and benefits
- Consider architectural implications

## Review Process

### Code Review
- All contributions require code review
- Address feedback constructively
- Ensure all CI checks pass
- Maintain backward compatibility when possible

### Acceptance Criteria
- Code follows established patterns
- Tests are comprehensive and pass
- Documentation is updated
- No breaking changes without justification

## License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.

## Questions?

- Join our [Discord community](https://discord.gg/tDajgYtBsZ)
- Open an issue for technical questions
- Check the [Wiki](https://github.com/shahmal1yev/blueskysdk/wiki) for documentation

---

We appreciate your contributions to making the BlueSky SDK better for everyone!